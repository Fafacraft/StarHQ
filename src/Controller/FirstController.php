<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FirstController extends AbstractController
{
    #[Route('/first/{name}', name: 'app_first', defaults:['name' => null], methods:['GET', 'HEAD'])]
    public function index($name): Response
    {
        $tests = ["A", "B", "C"];

        return $this->render('first/index.html.twig', [
            'controller_name' => 'FirstController',
            'tests' => $tests,
        ]);
    }
}
