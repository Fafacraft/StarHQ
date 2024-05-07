<?php

namespace App\Controller;

use App\Entity\Test;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FirstController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
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

    #[Route('/second', name: 'second')]
    public function second(EntityManagerInterface $em): Response
    {
        $repository = $this->em->getRepository(Test::class);
        $movies = $repository->findAll();

        dd($movies);
        return $this->render('index.html.twig');
    }
}
