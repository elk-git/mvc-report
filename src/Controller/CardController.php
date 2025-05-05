<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\DeckOfCards;
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
        return $this->render('card.html.twig');
    }

    #[Route("/card/deck", name: "card_deck")]
    public function card_deck(SessionInterface $session): Response
    {
        $this->ensureDeckExists($session);
        $deck = new DeckOfCards(json_decode($session->get('deck'), true));
        $data = [
            'deck' => $deck,
            'amountOfCards' => $deck->getAmountOfCards(),

        ];
        return $this->render('card_deck.html.twig', $data);
    }

    #[Route("/card/deck/shuffle", name: "card_deck_shuffle")]
    public function card_deck_shuffle(SessionInterface $session): Response
    {
        $this->ensureDeckExists($session);
        $deck = new DeckOfCards(json_decode($session->get('deck'), true));
        $deck->shuffle();
        $this->addFlash(
            'notice',
            'Kortleken har sorterats.'
        );
        $this->saveDeckToSession($session, $deck);
        $data = [
            'deck' => $deck,
            'amountOfCards' => $deck->getAmountOfCards(),

        ];
        return $this->render('card_deck.html.twig', $data);
    }

    #[Route("/card/deck/draw", name: "card_deck_draw")]
    public function card_deck_draw(SessionInterface $session): Response
    {
        $this->ensureDeckExists($session);
        $deck = new DeckOfCards(json_decode($session->get('deck'), true));
        $card = $deck->drawCard();
        if ($card === null) {
            $this->addFlash(
                'warning',
                'Inga kort kvar i kortleken.'
            );
        }

        $this->saveDeckToSession($session, $deck);
        $deck2 = new DeckOfCards([['value' => $card->getValue(), 'suit' => $card->getSuit()]]);

        $this->addFlash(
            'notice',
            'Drog kortet: ' . $card->getValue() . ' av ' . $card->getSuit()
        );
        $data = [
            'deck' => $deck2,
            'amountOfCards' => $deck->getAmountOfCards(),

        ];
        return $this->render('card_deck.html.twig', $data);
    }

    /**
     * Helper method checking if deck exists in session.
     */
    private function ensureDeckExists(SessionInterface $session): void
    {
        if (!$session->has('deck')) {
            $deck = new DeckOfCards();
            $session->set('deck', $deck->getJSONDeck());
            $this->addFlash(
                'warning',
                'Inget deck fanns sparat, skapade ett nytt.'
            );
        }
    }

    /**
     * Helper method to save the deck to the session.
     */
    private function saveDeckToSession(SessionInterface $session, DeckOfCards $deck): void
    {
        $session->set('deck', $deck->getJSONDeck());
        $this->addFlash(
            'notice',
            'Kortleken har sparats i sessionen.'
        );
    }
}
