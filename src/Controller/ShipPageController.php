<?php

namespace App\Controller;

use App\Repository\ShipImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ShipRepository;
use App\Entity\ShipImage;
use App\Entity\ShipPersonal;

class ShipPageController extends AbstractController
{
    
    private $shipRepository;
    private $em;
    public function __construct(EntityManagerInterface $em, ShipRepository $shipRepository){
        $this->em = $em;
        $this->shipRepository = $shipRepository;
    }

    #[Route('/ship/{name}', name: 'app_ship', defaults:['name' => null], methods:['GET', 'HEAD'])]
    public function ship_page($name, Request $request, LoggerInterface $logger): Response
    {
        if ($name == null) {
            return $this->redirectToRoute('app_home');
        }

        // check if ship exist
        $ship = $this->shipRepository->findOneByName($name);
        if ($ship == Null) {
            $logger->notice("Couldn't find ship named " . $name);
            return $this->redirectToRoute('app_all_ships');
        }

        // get ship image
        $imageLink = $this->em->getRepository(ShipImage::class)->findLinkByName($name);

        // get role color
        $role_color = getRoleColor($ship->getRole());

        // get nb of ship owned for the logged user
        $email = $this->getUser()->getUserIdentifier();
        $nb_ship = $this->em->getRepository(ShipPersonal::class)->findNbShips($email, $name);


        
        return $this->render('ship/ship_page.html.twig', [
            "shipImageLink" => $imageLink,
            "name" => $name,
            "ship" => $ship,
            "role_color" => $role_color,
            "nb_ship" => $nb_ship,
        ]);
    }
}