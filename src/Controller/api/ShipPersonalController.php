<?php

namespace App\Controller\api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\ShipPersonal;
use Doctrine\ORM\EntityManagerInterface;

class ShipPersonalController extends AbstractController
{
    // delete the ship with priority, name, and current user
    #[Route('/api/shippersonal/delete', name: 'api_shippersonal_delete')]
    public function deleteShip(EntityManagerInterface $em, Request $request): Response
    {
        $email = $this->getUser()->getUserIdentifier();
        $name = $request->query->get('name');
        $priority = $request->query->get('priority');
        $shipPersonal = $em->getRepository(ShipPersonal::class)->findPrecise($email, $name, $priority);

        $em->remove($shipPersonal);
        $em->flush();

        return $this->redirectToRoute('app_home');
    }

    // add the ship to shipPersonal of current user
    #[Route('/api/shippersonal/add', name: 'api_shippersonal_add')]
    public function addShip(EntityManagerInterface $em, Request $request): Response
    {
        $email = $this->getUser()->getUserIdentifier();
        $name = $request->query->get('name');

        $shipPersonal = new ShipPersonal();
        $shipPersonal->setEmail($email);
        $shipPersonal->setName($name);

        $em->persist($shipPersonal);
        $em->flush();

        return $this->redirectToRoute('app_home');
    }
}
