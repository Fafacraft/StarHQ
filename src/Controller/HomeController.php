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

class HomeController extends AbstractController
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


    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        $ships = $this->getAllUserShips();
        return $this->render('home/home.html.twig', [
            'ships' => $ships,
        ]);
    }

    private function getAllUserShips():array
    {
        // get all user shipPersonal
        $shipsPersonals = $this->getAllShipFromCurrentUser();

        // get all necessary info from the ships
        $ships = array();
        foreach ($shipsPersonals as $shipPersonal) {
            $name = $shipPersonal->getName();
            $priority = $shipPersonal->getPriority();

            $ship = $this->shipRepository->findOneByName($name);
            if ($ship == null) {
                $this->logger->warning("Couldn't find ship named " . $name);
                continue;
            }
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
            $ship_clear['priority'] = $priority;

            // change the color of the "role" part on the ship card, depending of each role
            switch ($ship_clear['role']) {
                case "combat":
                    $ship_clear['role_color'] = "#e23535"; // red
                    break;
                case "exploration":
                    $ship_clear['role_color'] = "#2d89e1"; // blue
                    break;
                case "competition":
                    $ship_clear['role_color'] = "#08b122"; // green
                    break;
                case "industrial":
                    $ship_clear['role_color'] = "#f8d142"; // yellow
                    break;
                case "multi":
                    $ship_clear['role_color'] = "#3aebc8"; // cyan
                    break;
                case "ground":
                    $ship_clear['role_color'] = "#a96f4c"; // brown
                    break;
                case "support":
                    $ship_clear['role_color'] = "#f2aae4"; // pink
                    break;
                case "transport":
                    $ship_clear['role_color'] = "#f68948"; // orange
                    break;
                default:
                    $ship_clear['role_color'] = "";
            }

            array_push($ships, $ship_clear);
        }

        return $ships;
    }

    private function getAllShipFromCurrentUser(): array
    {
        $email = $this->getUser()->getUserIdentifier();
        $ships = $this->shipPersonalRepository->findByUser($email);


        return $ships;
    }
}