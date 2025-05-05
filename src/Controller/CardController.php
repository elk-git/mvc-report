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
        if ($deck->isEmpty()) {
            $this->addFlash(
                'warning',
                'Inga kort kvar i kortleken. Kunde inte blanda kortleken. Tips: Töm sessionen!'
            );
            return $this->render('card.html.twig');
        } else {
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
    }

    #[Route("/card/deck/draw", name: "card_deck_draw")]
    public function card_deck_draw(SessionInterface $session): Response
    {
        $this->ensureDeckExists($session);
        $deck = new DeckOfCards(json_decode($session->get('deck'), true));
        if ($deck->isEmpty()) {
            $this->addFlash(
                'warning',
                'Inga kort kvar i kortleken. Kunde inte dra kort. Tips: Töm sessionen!'
            );
            return $this->render('card.html.twig');
        } else {
            $card = $deck->drawCard();

            if ($card === null) {
                $this->addFlash(
                    'warning',
                    'Inga kort kvar i kortleken. Kunde inte dra kort.'
                );
                return $this->render('card.html.twig');
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
    }

    #[Route("/card/deck/draw/{num<\d+>}", name: "card_deck_draw_num")]
    public function card_deck_draw_num(SessionInterface $session, int $num): Response
    {
        if ($num < 1 || $num > 52) {
            $this->addFlash(
                'warning',
                'Ogiltigt antal kort. Vänligen ange ett nummer mellan 1 och 52.'
            );
            return $this->redirectToRoute('card_deck');
        }
        $this->ensureDeckExists($session);
        $deck = new DeckOfCards(json_decode($session->get('deck'), true));

        if ($deck->isEmpty()) {
            $this->addFlash(
                'warning',
                'Kortleken är tom. Tips: töm sessionen!'
            );
            return $this->render('card.html.twig');
        } else {
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

            $this->saveDeckToSession($session, $deck);
            $deck2 = new DeckOfCards(array_map(fn($drawnCard) => ['value' => $drawnCard->getValue(), 'suit' => $drawnCard->getSuit()], $cards));

            $data = [
                'deck' => $deck2,
                'amountOfCards' => $deck->getAmountOfCards(),

            ];
            return $this->render('card_deck.html.twig', $data);
        }
    }

    #[Route("/card/deck/reset", name: "card_deck_reset")]
    public function card_deck_reset(SessionInterface $session): Response
    {
        $session->remove('deck');
        $this->addFlash(
            'warning',
            'Kortleken har resettats.'
        );
        return $this->redirectToRoute('card_deck');
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
