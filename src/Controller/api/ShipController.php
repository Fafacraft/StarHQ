<?php

namespace App\api\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ShipRepository;
use PhpParser\Node\Name;

class ShipController extends AbstractController
{
    private $shipRepository;

    public function __construct(ShipRepository $shipRepository)
    {
        $this->shipRepository = $shipRepository;
    }

    // return all ships with this approximate name
    #[Route('/api/ship', name: 'api_ships')]
    public function getShips(Request $request): JsonResponse
    {
        $searchTerm = $request->query->get('q');
        $ships = $this->shipRepository->findByNameLike($searchTerm);

        // get only the names
        $shipNames = [];
        foreach ($ships as $ship) {
            $shipNames[] = $ship->getName();
        }

        return new JsonResponse($shipNames);
    }
}
