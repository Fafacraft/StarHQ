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
    public function index(Request $request): Response
    {
        $role = $request->query->get('role');
        $size = $request->query->get('size');
        $manufacturer = $request->query->get('manufacturer');

        $ships = array();
        if ($role != null) {
            $ships = $this->shipRepository->findAllShipsByRole($role);
        } else if ($size != null) {
            $ships = $this->shipRepository->findAllShipsBySize($size);
        } else if ($manufacturer != null) {
            $manufacturer_array = [];
            switch ($manufacturer) {
                case "aegis": $manufacturer_array = ["Aegis Dynamics"];break;
                case "alien": $manufacturer_array = ["Aopoa", "Banu", "Gatac Manufacture"];break;
                case "anvil": $manufacturer_array = ["Anvil Aerospace"];break;
                case "argo": $manufacturer_array = ["Argo Astronautics"];break;
                case "crusader": $manufacturer_array = ["Crusader Industries"];break;
                case "drake": $manufacturer_array = ["Drake Interplanetary"];break;
                case "esperia": $manufacturer_array = ["Esperia"];break;
                case "mirai": $manufacturer_array = ["Mirai"];break;
                case "misc": $manufacturer_array = ["MISC", "Musashi Industrial and Starflight Concern"];break;
                case "origin": $manufacturer_array = ["Origin Jumpworks"];break;
                case "rsi": $manufacturer_array = ["Roberts Space Industries"];break;
                case "tumbril": $manufacturer_array = ["Tumbril", "Tumbril Land Systems"];break;
                case "other": $manufacturer_array = ["Consolidated Outland", "Greycat Industrial", "Kruger Intergalatic"];
            };
            $ships = $this->shipRepository->findAllShipsByManufacturer($manufacturer_array);

        } else { 
            $ships = $this->shipRepository->findAllShips();
        }


        if ($role == null) {$role = "Role";}
        if ($size == null) {$size = "Size";}
        if ($manufacturer == null) {$manufacturer = "Manufacturer";}

        
        $ships_final = $this->getAllShips($ships);

        return $this->render('ship/all_ships.html.twig', [
            'ships' => $ships_final,
            'role' => $role,
            'size' => $size,
            'manufacturer' => $manufacturer,
        ]);
    }

    private function getAllShips($ships):array
    {
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