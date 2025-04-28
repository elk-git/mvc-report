<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuoteControllerJson
{
    #[Route("/api/quote", name: "/api/quote")]
    public function jsonQuote(): Response
    {
        $quote = [
            'The only limit to our realization of tomorrow is our doubts of today.',
            'The future belongs to those who believe in the beauty of their dreams.',
            'It does not matter how slowly you go as long as you do not stop.',
        ];

        // GET RANDOM QUOTE
        $randomQuote = $quote[array_rand($quote)];

        $data = [
            'quote' => $randomQuote,
            'date' => date('Y-m-d'),
            'timestamp' => time(),
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}


