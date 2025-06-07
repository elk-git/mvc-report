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
                [
                    'request' => 'GET',
                    'url' => '/api/deck',
                    'description' => 'Hämtar kortleken i sessionen och sorterar den.',
                ],
                [
                    'request' => 'POST',
                    'url' => '/api/deck/shuffle',
                    'description' => 'Blandar kortleken.',
                    'redirect' => '/form/api/deck/shuffle', // Needs a form to be submitted.
                ],
                [
                    'request' => 'POST',
                    'url' => '/api/deck/draw/',
                    'description' => 'Drar 1 kort från kortleken.',
                    'redirect' => '/form/api/deck/draw', // needs a form to be submitted.
                ],

                [
                    'request' => 'POST',
                    'url' => '/api/deck/draw/:number',
                    'description' => 'Drar ett x antal (:number) kort från kortleken. Om inget antal anges dras ett kort.',
                    'redirect' => '/form/api/deck/draw/number', // needs a form to be submitted.
                ],
                [
                    'request' => 'GET',
                    'url' => '/api/deck/reset',
                    'description' => 'Återställer kortleken till en ny sorterad kortlek.',
                ],
                [
                    'request' => 'GET',
                    'url' => '/api/game',
                    'description' => 'Visar upp aktuell ställning i blackjack spelet.',
                ],
                [
                    'request' => 'GET',
                    'url' => '/api/library/books',
                    'description' => 'Hämtar alla böcker i biblioteket.',
                ],
                [
                    'request' => 'GET',
                    'url' => '/api/library/show/{isbn}',
                    'description' => 'Hämtar en specifik bok med ISBN. Exemplet skickar dig till ett exmpel-ISBN.',
                    'redirect' => 'api_library_show_example',
                ]
            ],
        ];
        return $this->render('api.html.twig', $data);
    }
}
