<?php

namespace App\Controller;

use App\Card\Card;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CardController extends AbstractController
{
    #[Route("/card", name: "card")]
    public function card(): Response
    {
        $card = new Card('32319');
        $data = [
            'card' => $card->getValue(),

        ];
        return $this->render('card.html.twig', $data);
    }

    #[Route("/card/deck", name: "card_deck")]
    public function card_deck(): Response
    {
        $data = [
            'card' => null,

        ];
        return $this->render('card.html.twig', $data);
    }

    #[Route("/card/deck/shuffle", name: "card_deck_shuffle")]
    public function card_deck_shuffle(): Response
    {
        $data = [
            'card' => null,

        ];
        return $this->render('card.html.twig', $data);
    }

    #[Route("/card/deck/draw", name: "card_deck_draw")]
    public function card_deck_draw(): Response
    {
        $data = [
            'card' => null,

        ];
        return $this->render('card.html.twig', $data);
    }
}
