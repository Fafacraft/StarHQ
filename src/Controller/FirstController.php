<?php

namespace App\Controller;

use App\Entity\Ship;
use App\Entity\Test;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ShipRepository;
use PHPUnit\Framework\Error\Notice;

class FirstController extends AbstractController
{
    
    private $shipRepository;
    private $em;
    public function __construct(EntityManagerInterface $em, ShipRepository $shipRepository){
        $this->em = $em;
        $this->shipRepository = $shipRepository;
    }

    #[Route('/first/{name}', name: 'app_first', defaults:['name' => null], methods:['GET', 'HEAD'])]
    public function index($name): Response
    {
        $tests = ["A", "B", "C"];

        return $this->render('first/index.html.twig', [
            'controller_name' => 'FirstController',
            'tests' => $tests,
        ]);
    }

    #[Route('/ship_page', name: 'ship_page')]
    public function second(Request $request, LoggerInterface $logger): Response
    {
        $shipName = $request->query->get('name');
        $ship = $this->shipRepository->findOneByName($shipName);

        // if we could not find the ship
        if ($ship == Null) {
            $logger->notice("Couldn't find ship named " . $shipName);
            return $this->redirectToRoute('app_first');
        }
        print("This is " . $shipName . " ship page");
        return new Response();
    }
}
