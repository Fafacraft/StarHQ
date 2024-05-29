<?php

namespace App\Controller;

use App\Repository\ShipImageRepository;
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
    private $logger;
    public function __construct(ShipRepository $shipRepository, ShipImageRepository $shipImageRepository, LoggerInterface $logger){
        $this->shipRepository = $shipRepository;
        $this->shipImageRepository = $shipImageRepository;
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
        // TODO: get all ships name the logged in user has
        $ship_names = ["400i", "Aurora CL", "350r"];

        // get all necessary info from the ships
        $ships = array();

        foreach ($ship_names as $name) {
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
                // Clean up the key
                $cleanKey = preg_replace('/.*\0/', '', $key);
                $ship_clear[$cleanKey] = $value;
            }
            $ship_clear['imageLink'] = $imageLink;
            array_push($ships, $ship_clear);
        }

        return $ships;
    }
}