<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route("/api", name: "api")]
    public function api(): Response
    {
        $data = [
            'routes' => [
                [
                    'request' => 'GET',
                    'url' => '/api/quote',
                    'description' => 'Hämtar ett slumpmässigt citat, dagens datum och timestamp.',
                ],
            ],
        ];
        return $this->render('api.html.twig', $data);
    }
}


