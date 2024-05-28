<?php

namespace App\Controller;

use App\Entity\Ship;
use App\Entity\Test;
use App\Repository\ShipImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ShipRepository;
use PHPUnit\Framework\Error\Notice;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index', defaults:['name' => null], methods:['GET', 'HEAD'])]
    public function index($name, Request $request, LoggerInterface $logger): Response
    {
        return $this->render('ship/ship_card.html.twig', [
        ]);
    }
}