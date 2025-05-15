<?php

namespace App\Controller;

use App\Card\DeckOfCards;
use App\Controller\CardController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ApiDeckController extends AbstractController
{
    private CardController $cardController;

    public function __construct(CardController $cardController)
    {
        $this->cardController = $cardController;
    }

    #[Route("/api/deck", name: "/api/deck", methods: ['GET'])]
    public function apiDeck(SessionInterface $session): Response
    {
        $deck = $this->cardController->getDeckAndSaveToSession($session);
        if ($deck->isEmpty()) {
            return new JsonResponse([
                'error' => 'Inga kort kvar i kortleken. Kan inte sortera.'
            ]);
        }
        $deck->sort();

        $data = json_decode($deck->getJSONDeck(), true);
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/form/api/deck/shuffle", name: "/form/api/deck/shuffle")]
    public function formApiDeckShuffle(Request $request): Response
    {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('/api/deck/shuffle'))
            ->setMethod('POST')
            ->add('shuffle', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        return $this->render('deck_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/api/deck/shuffle", name: "/api/deck/shuffle", methods: ['POST'])]
    public function apiDeckShuffle(SessionInterface $session): Response
    {
        $deck = $this->cardController->getDeckAndSaveToSession($session);
        if ($deck->isEmpty()) {
            return new JsonResponse([
                'error' => 'Inga kort kvar i kortleken. Kunde inte blanda kortleken.'
            ]);
        }
        $deck->shuffle();
        $this->cardController->getDeckAndSaveToSession($session, $deck);

        $data = json_decode($deck->getJSONDeck(), true);
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/form/api/deck/draw", name: "/form/api/deck/draw")]
    public function formApiDeckDraw(Request $request): Response
    {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('/api/deck/draw'))
            ->setMethod('POST')
            ->add('submit', SubmitType::class, [
                'label' => 'Dra kort',
            ])
            ->getForm();

        $form->handleRequest($request);

        return $this->render('deck_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/form/api/deck/draw/number", name: "/form/api/deck/draw/number")]
    public function formApiDeckDrawNumber(Request $request): Response
    {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('/api/deck/draw/:number'))
            ->setMethod('POST')
            ->add('number', TextType::class, [
                'label' => 'Antal kort att dra',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Dra kort',
            ])
            ->getForm();

        $form->handleRequest($request);

        return $this->render('deck_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/api/deck/draw", name: "/api/deck/draw", methods: ['POST'])]
    #[Route("/api/deck/draw/", name: "/api/deck/draw/", methods: ['POST'])]
    #[Route("/api/deck/draw/{number}", name: "/api/deck/draw/:number", methods: ['POST'])]
    public function apiDeckDrawNumber(Request $request, SessionInterface $session, int $number = 1): Response
    {
        $requestData = $request->request->all();
        $requestNumber = $requestData['form']['number'] ?? null;
        if ($requestNumber) {
            $number = $requestNumber;
        }

        if (!is_numeric($number) || $number < 1) {
            return new JsonResponse([
                'error' => 'Ogiltigt antal kort angivet. Antingen inte ett tal eller mindre än 1.'
            ]);
        }

        $deck = $this->cardController->getDeckAndSaveToSession($session);
        if ($deck->isEmpty()) {
            return new JsonResponse([
                'error' => 'Inga kort kvar i kortleken. Kunde inte dra kort.'
            ]);
        }
        $drawnCards = [];
        for ($i = 0; $i < $number; $i++) {
            $card = $deck->drawCard();
            if ($card) {
                $drawnCards[] = [
                    'value' => $card->getValue(),
                    'suit' => $card->getSuit(),
                ];
            }
        }

        $this->cardController->getDeckAndSaveToSession($session, $deck);

        return new JsonResponse([
            'Antal kort kvar' => $deck->getAmountOfCards(),
            'Kort' => $drawnCards,
        ]);
    }

    #[Route("/api/deck/reset", name: "/api/deck/reset", methods: ['GET'])]
    public function apiDeckReset(SessionInterface $session): Response
    {
        $session->remove('deck');
        $deck = $this->cardController->getDeckAndSaveToSession($session);

        $data = json_decode($deck->getJSONDeck(), true);
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
