<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionController extends AbstractController
{
    #[Route("/session", name: "session")]
    public function session(Request $request, SessionInterface $session): Response
    {
        // if (!$session->get("session_id")) {
        //     $session->start();
        //     $session->set("session_id", $session->getId());
        //     $this->addFlash(
        //         'notice',
        //         'Sessionen initierades.'
        //     );
        // };
        $data = [
            'session' => $session->all(),

        ];
        return $this->render('session.html.twig', $data);
    }

    #[Route("/session/delete", name: "session_delete")]
    public function sessionDelete(SessionInterface $session): Response
    {

        $session->clear();
        $this->addFlash(
            'warning',
            'Sessionen raderades.'
        );
        return $this->redirectToRoute('session');
    }
}
