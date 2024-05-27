<?php

namespace App\Command;

use App\Entity\ShipImage;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ShipRepository;
use App\Repository\ShipImageRepository;
use DOMElement;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:load-ship-images')]
class LoadShipImages extends Command
{
    protected static $defaultName = 'app:load-ship-images';

    private $entityManager;
    private $shipRepository;
    private $shipImageRepository;
    private $logger;

    public function __construct(EntityManagerInterface $entityManager, ShipRepository $shipRepository, ShipImageRepository $shipImageRepository, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->shipRepository = $shipRepository;
        $this->shipImageRepository = $shipImageRepository;
        $this->logger = $logger;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Loads all ships images from the pledge link.')
            ->setHelp('This command (re)loads all ship images from the pledge link.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Loading ship images...');

        $pledgeLinks = $this->shipRepository->findAllPledgeLinks();
        foreach ($pledgeLinks as $pledgeLink) { 
            $name = $pledgeLink['Name'];
            $output->writeln('loading ' . $name . ' image...');
            $imageLink = $this->scrapeImageUrl($pledgeLink['PledgeLink']);
            
            if ($imageLink == null) {
                $output->writeln('failed to find image');
                continue;
            }

            // some are not hosted on their media server, but on the website server, so ye, some post processing
            // if it start with /media, add the website before it
            if (strpos($imageLink, '/https') !== 0) {
                $imageLink = 'https://robertsspaceindustries.com/' . $imageLink;
            }


            // search the ship image already in the db and remove it, as we're going to replace it
            $find_ship = $this->shipImageRepository->FindOneByName($name);
            if ($find_ship) {
                $this->entityManager->remove($find_ship);
                $this->entityManager->flush();
            }
            // add shipImage
            $shipImage = new ShipImage();
            $shipImage->setName($name);
            $shipImage->setImageLink($imageLink);
            $this->entityManager->persist($shipImage);
            $this->entityManager->flush();
        }

        $io = new SymfonyStyle($input, $output);
        $io->success('Star Citizen ship images loaded successfully!');

        return Command::SUCCESS;
    }


    private function scrapeImageUrl($url) {
        // Load the HTML into a DOMDocument
        $dom = new \DOMDocument();
        @$dom->loadHTMLFile($url);
    
        // Create a new DOMXPath instance
        $xpath = new \DOMXPath($dom);   
    
        $query = "//*[@id='intro']/div[1]/div[2]/img";
    
        // Execute the query
        $result = $xpath->query($query);
    
        // Check if the query returned any results
        if ($result->length > 0) {
            // Get the 'src' attribute of the first matching element
            $e = $result->item(0);
            if ($e instanceof DOMElement) {
                // idk how to disable this error but it is no error ; he just doesn't understand that getAttribute is called for a DOMElement object, not DOMNode.
                $imageUrl = $result->item(0)->getAttribute('src'); //noinspection PhpUndefinedMethodInspection
                // ^ this is *annoying* D:
            } else {return null;}
            return $imageUrl;
        } else {
            return null;
        }
    }
}