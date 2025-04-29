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
        $session->set('name', 'elis');
        $session->set('age', 25);
        $session->set('email', 'elsidaskdak@kdmga.com');
        $data = [
            'session' => $session->all(),

        ];
        return $this->render('session.html.twig', $data);
    }
}
