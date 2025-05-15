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
    public function cardDeck(SessionInterface $session): Response
    {
        $deck = $this->getDeckAndSaveToSession($session);
        $deck->sort();
        $this->addFlash(
            'notice',
            'Kortleken har sorterats.'
        );
        $this->getDeckAndSaveToSession($session, $deck);
        $data = [
            'deck' => $deck,
            'amountOfCards' => $deck->getAmountOfCards(),
        ];
        return $this->render('card_deck.html.twig', $data);
    }

    #[Route("/card/deck/shuffle", name: "card_deck_shuffle")]
    public function cardDeckShuffle(SessionInterface $session): Response
    {
        $deck = $this->getDeckAndSaveToSession($session);
        if ($deck->isEmpty()) {
            $this->addFlash(
                'warning',
                'Inga kort kvar i kortleken. Kunde inte blanda kortleken. Tips: Töm sessionen!'
            );
            return $this->render('card.html.twig');
        }

        $deck->shuffle();
        $this->addFlash(
            'notice',
            'Kortleken har shufflats.'
        );
        $this->getDeckAndSaveToSession($session, $deck);

        $data = [
            'deck' => $deck,
            'amountOfCards' => $deck->getAmountOfCards(),
        ];
        return $this->render('card_deck.html.twig', $data);
    }

    #[Route("/card/deck/draw", name: "card_deck_draw")]
    public function cardDeckDraw(SessionInterface $session): Response
    {
        $deck = $this->getDeckAndSaveToSession($session);
        if ($deck->isEmpty()) {
            $this->addFlash(
                'warning',
                'Inga kort kvar i kortleken. Kunde inte dra kort. Tips: Töm sessionen!'
            );
            return $this->render('card.html.twig');
        }

        $card = $deck->drawCard();
        if ($card === null) {
            $this->addFlash(
                'warning',
                'Inga kort kvar i kortleken. Kunde inte dra kort.'
            );
            return $this->render('card.html.twig');
        }

        $this->getDeckAndSaveToSession($session, $deck);
        $deck2 = new DeckOfCards([$card]);

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

    #[Route("/card/deck/draw/{num<\d+>}", name: "card_deck_draw_num")]
    public function cardDeckDrawNum(SessionInterface $session, int $num): Response
    {
        if ($num < 1 || $num > 52) {
            $this->addFlash(
                'warning',
                'Ogiltigt antal kort. Vänligen ange ett nummer mellan 1 och 52.'
            );
            return $this->redirectToRoute('card_deck');
        }

        $deck = $this->getDeckAndSaveToSession($session);
        if ($deck->isEmpty()) {
            $this->addFlash(
                'warning',
                'Kortleken är tom. Tips: töm sessionen!'
            );
            return $this->render('card.html.twig');
        }

        $cards = [];
        for ($i = 0; $i < $num; $i++) {
            $card = $deck->drawCard();
            if ($card === null) {
                $this->addFlash(
                    'warning',
                    'Inga kort kvar i kortleken. Kunde inte dra fler kort.'
                );
                break;
            }
            $cards[] = $card;
        }

        $deck2 = new DeckOfCards($cards);
        $this->getDeckAndSaveToSession($session, $deck);

        $data = [
            'deck' => $deck2,
            'amountOfCards' => $deck->getAmountOfCards(),
        ];
        return $this->render('card_deck.html.twig', $data);
    }

    #[Route("/card/deck/reset", name: "card_deck_reset")]
    public function cardDeckReset(SessionInterface $session): Response
    {
        $session->remove('deck');
        $this->addFlash(
            'warning',
            'Kortleken har resettats.'
        );
        return $this->redirectToRoute('card_deck');
    }

    public function getDeckAndSaveToSession(SessionInterface $session, ?DeckOfCards $deck = null): DeckOfCards
    {
        if ($deck !== null) {
            $session->set('deck', $deck);
            return $deck;
        }

        if (!$session->has('deck')) {
            $deck = new DeckOfCards();
            $session->set('deck', $deck);
            return $deck;
        }

        $sessionDeck = $session->get('deck');
        if (!$sessionDeck instanceof DeckOfCards) {
            $deck = new DeckOfCards();
            $session->set('deck', $deck);
            return $deck;
        }
        return $sessionDeck;
    }
}
