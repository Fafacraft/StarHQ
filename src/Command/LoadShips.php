<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ShipRepository;
use App\Entity\Ship;
use Psr\Log\LoggerInterface;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:load-ships')]
class LoadShips extends Command
{
    protected static $defaultName = 'app:load-ships';

    private $entityManager;
    private $shipRepository;
    private $logger;

    public function __construct(EntityManagerInterface $entityManager, ShipRepository $shipRepository, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->shipRepository = $shipRepository;
        $this->logger = $logger;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Loads all star citizen from the Deutsch Star Citizen wiki API to the database.')
            ->setHelp('This command (re)loads all star citizen from the Deutsch Star Citizen wiki API to the database. It is doing 200+ calls, please don\'t spam it');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Loading ships... For more info, read the server logs');
        $this->pushAllShips($this->entityManager, $this->shipRepository, $this->logger, $output);

        $io = new SymfonyStyle($input, $output);
        $io->success('Star Citizen ships loaded successfully!');

        return Command::SUCCESS;
    }

    private function pushAllShips($entityManager, $shipRepository, $logger, $output) {
        // get the number of ships today
        $total_ships_raw = file_get_contents("https://api.star-citizen.wiki/api/v2/vehicles?limit=1");
        if ($total_ships_raw === false) {
            throw new \Exception('Error fetching ships data from API.');
        }
        $total_ships = json_decode($total_ships_raw, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Error parsing JSON ships data.');
        }

        $total_int = $total_ships['meta']['total'];

        $output->writeln((string)$total_int . ' ships found');

        // get all ships link from the API
        $total_ships_raw = file_get_contents("https://api.star-citizen.wiki/api/v2/vehicles?limit=" . (string)$total_int);
        if ($total_ships_raw === false) {
            throw new \Exception('Error fetching ships data from API.');
        }
        $total_ships = json_decode($total_ships_raw, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Error parsing JSON ships data.');
        }

        $i = 0;
        // for each ships, get the proper data and push it
        foreach ($total_ships['data'] as $ship_raw) {
            // get the data
            $ship_clean = $this->getShipData($logger, $ship_raw, $output);
            if ($ship_clean == null) {
                // one ship was broken, it happens
                continue;
            }
            // push to the Db
            $this->addToDb($entityManager, $shipRepository, $ship_clean);
            $i += 1;
            $output->writeln((string) $i . '/' . (string)$total_int . ' loaded');
        }

        return;
    }

    private function getShipData($logger, $ship_raw, $output) {
        // call the ship data and json'it
        try {
            $ship_data_raw = file_get_contents($ship_raw['link']);
        } catch(Exception $e) {
            // Some werdness in the API of ships existing in the list but not having their own data. May be thrown if API is down.
            $logger->warning("Couldn't get data from " . (string)$ship_raw['link']);
            return null;
        }
        $ship_data = json_decode($ship_data_raw, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Error parsing JSON ship data.');
        }
        $logger->debug('Call to the API of ' . $ship_data['data']['name']);
        $output->writeln('Loading ' . $ship_data['data']['name'] . '..................');

        // get all the data we dream and need to have in our database
        $ship_clean = array();
        $ship_clean['Name'] = $ship_data['data']['name'];
        $ship_clean['Mass'] = $ship_data['data']['mass'];
        $ship_clean['Cargo_capacity'] = $ship_data['data']['cargo_capacity'];
        try {
            $ship_clean['Hp'] = $ship_data['data']['health'];
            $ship_clean['Hp_shield'] = $ship_data['data']['shield_hp'];
            $ship_clean['Speed_scm'] = $ship_data['data']['speed']['scm'];
            $ship_clean['Speed_max'] = $ship_data['data']['speed']['max'];
            $ship_clean['Speed_quantum'] = $ship_data['data']['quantum']['quantum_speed'];
        } catch(Exception $e) {
            $logger->notice($ship_data['data']['name'] . " has no detailed info on speed and/or hp");
            $output->writeln('No detailed info');
        }
        $ship_clean['Role'] = $ship_data['data']['type']['en_EN'];
        $ship_clean['Description'] = $ship_data['data']['description']['en_EN'];
        try {
            $ship_clean['Size'] = $ship_data['data']['size']['en_EN'];
        } catch (Exception $e) {
            $logger->notice($ship_data['data']['name'] . " has no size");
            $output->writeln('No size');
        }
        $ship_clean['Manufacturer'] = $ship_data['data']['manufacturer']['name'];
        try {
            $ship_clean['Irl_price'] = $ship_data['data']['skus'][0]['price'];
        } catch (Exception $e) {
            $logger->notice($ship_data['data']['name'] . " has no irl_price");
            $output->writeln('No irl price');
        }
        try {
            $ship_clean['PledgeLink'] = $ship_data['data']['pledge_url'];
        } catch(Exception $e) {
            $logger->notice($ship_data['data']['name'] . " has no pledge link");
            $output->writeln('No pledge link');
        }

        return $ship_clean;
    }

    private function addToDb($entityManager, $shipRepository, $ship_data) {
        // search the ship already in the db and remove it, as we're going to replace it
        $find_ship = $shipRepository->FindOneByName($ship_data['Name']);
        if ($find_ship) {
            $entityManager->remove($find_ship);
            $entityManager->flush();
        }

        $ship = new Ship();
        $ship->setName($ship_data['Name']);
        $ship->setMass((int)$ship_data['Mass']);
        $ship->setCargoCapacity((int)$ship_data['Cargo_capacity']);
        try {
            $ship->setHp((int)$ship_data['Hp']);
            $ship->setHpShield((int)$ship_data['Hp_shield']);
            $ship->setSpeedScm((int)$ship_data['Speed_scm']);
            $ship->setSpeedMax((int)$ship_data['Speed_max']);
            $ship->setSpeedQuantum((int)$ship_data['Speed_quantum']);
        } catch (Exception $e) {
            // no advanced data found, already noticed
        }
        $ship->setRole($ship_data['Role']);
        $ship->setDescription($ship_data['Description']);
        try {
            $ship->setSize($ship_data['Size']);
        } catch (Exception $e) {
            // no size, already noticed
        }
        $ship->setManufacturer($ship_data['Manufacturer']);
        try {
            $ship->setIrlPrice((int)$ship_data['Irl_price']);
        } catch(Exception $e) {
            // no Irl price found, already noticed
        };
        try {
            $ship->setPledgeLink($ship_data['PledgeLink']);
        } catch(Exception $e) {
            // no pledge link found, already noticed
        };
        

        // tell the entitymanager to persist this new object, and then to actually insert it
        $entityManager->persist($ship);
        $entityManager->flush();
    }
}
