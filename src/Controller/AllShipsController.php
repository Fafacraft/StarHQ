<?php

namespace App\Controller;

use App\Repository\ShipImageRepository;
use App\Repository\ShipPersonalRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ShipRepository;

class AllShipsController extends AbstractController
{
    private $shipRepository;
    private $shipImageRepository;
    private $shipPersonalRepository;
    private $logger;
    public function __construct(ShipRepository $shipRepository, ShipImageRepository $shipImageRepository, ShipPersonalRepository $shipPersonalRepository, LoggerInterface $logger){
        $this->shipRepository = $shipRepository;
        $this->shipImageRepository = $shipImageRepository;
        $this->shipPersonalRepository = $shipPersonalRepository;
        $this->logger = $logger;
    }


    #[Route('/all_ships', name: 'app_all_ships')]
    public function index(): Response
    {
        $ships = $this->getAllShips();
        return $this->render('ship/all_ships.html.twig', [
            'ships' => $ships,
        ]);
    }

    private function getAllShips():array
    {
        $ships = $this->shipRepository->findAllShips();

        $ships_final = array();

        // get all necessary info from the ships
        foreach ($ships as $ship) {
            $name = $ship->getName();

            $imageLink = $this->shipImageRepository->findLinkByName($name);

            // easier to handle as an array
            $ship = (array) $ship; 
            // do a bit of cleanup  App\Entity\Name -> name
            $ship_clear = [];
            foreach ($ship as $key => $value) {
                $cleanKey = preg_replace('/.*\0/', '', $key);
                $ship_clear[$cleanKey] = $value;
            }
            $ship_clear['imageLink'] = $imageLink;

            // change the color of the "role" part on the ship card, depending of each role
            $ship_clear['role_color'] = getRoleColor($ship_clear['role']);
            $ship_clear['with_button'] = false;

            array_push($ships_final, $ship_clear);
        }

        return $ships_final;
    }
}