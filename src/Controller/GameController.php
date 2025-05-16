<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Game\Game;
use App\Game\PlayerActions;
use App\Game\DealerActions;
use App\Game\DeckOfCards;
use Symfony\Component\HttpFoundation\JsonResponse;

class GameController extends AbstractController
{
    #[Route("/game", name: "game")]
    public function game(): Response
    {
        return $this->render('game.html.twig');
    }

    #[Route("/game/doc", name: "game/doc")]
    public function gameDocumentation(): Response
    {
        return $this->render('game_doc.html.twig');
    }

    #[Route("/game/start", name: "game/start")]
    public function gameStart(SessionInterface $session): Response
    {
        $game = $this->getGameAndSave($session);


        $data = [
            'player' => $game->getPlayer()->getHand(),
            'dealer' => $game->getDealer()->getHand(),
            'gameMessage' => $game->getGameMessage(),
            'isGameDone' => $game->isGameDone(),
        ];
        return $this->render('blackjack.html.twig', $data);
    }

    #[Route("/game/hit", name: "game/hit")]
    public function gameHit(SessionInterface $session): Response
    {
        $game = $this->getGameAndSave($session);
        $game->playerHit();
        $data = [
            'player' => $game->getPlayer()->getHand(),
            'dealer' => $game->getDealer()->getHand(),
            'gameMessage' => $game->getGameMessage(),
            'isGameDone' => $game->isGameDone(),
        ];
        return $this->render('blackjack.html.twig', $data);
    }

    #[Route("/game/stand", name: "game/stand")]
    public function gameStand(SessionInterface $session): Response
    {
        $game = $this->getGameAndSave($session);
        $game->playerStand();
        $data = [
            'player' => $game->getPlayer()->getHand(),
            'dealer' => $game->getDealer()->getHand(),
            'gameMessage' => $game->getGameMessage(),
            'isGameDone' => $game->isGameDone(),
        ];
        return $this->render('blackjack.html.twig', $data);
    }

    #[Route("/game/reset", name: "game/reset")]
    public function gameReset(SessionInterface $session): Response
    {
        $session->remove('game');
        return $this->redirectToRoute('game');
    }

    // API

    #[Route("/api/game", name: "/api/game", methods: ['GET'])]
    public function apiDeckReset(SessionInterface $session): Response
    {

        $game = $session->get('game') ?? ['message' => 'Inget spel sparat.'];
        $data = json_decode($game->getJSONGame(), true);
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    // Static method
    public function getGameAndSave(SessionInterface $session, ?Game $game = null): Game
    {
        if ($game !== null) {
            $session->set('game', $game);
            return $game;
        }

        if (!$session->has('game')) {
            $this->addFlash(
                'notice',
                'Inget spel sparat, startade nytt spel.'
            );
            $game = new Game();
            $session->set('game', $game);
            return $game;
        }

        $sessionGame = $session->get('game');
        if (!$sessionGame instanceof Game) {
            $this->addFlash(
                'notice',
                'Inget spel sparat, startade nytt spel.'
            );
            $game = new Game();
            $session->set('game', $game);
            return $game;
        }
        $this->addFlash(
            'notice',
            'Spel sparat, återupptog spelet.'
        );
        return $sessionGame;
    }
}
