<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Game\Game;
use App\Game\PlayerActions;
use App\Game\DealerActions;
use App\Game\DeckOfCards;

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
    public function gameStart(): Response
    {
        $game = new Game();
        $game->startGame();

        $data = [
            'player' => $game->getPlayer(),
            'dealer' => $game->getDealer(),
            'endMessage' => $game->getEndMessage(),
        ];
        return $this->render('blackjack.html.twig', $data);
    }
}
