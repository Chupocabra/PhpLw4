<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;

class LogoutController extends AbstractController
{
    /**
     * Передаем сессию чтобы очистить ее
     * @param Session $session
     * @return Response
     */
    #[Route('/logout', name: 'app_logout')]
    public function index(Session $session) : Response
    {
        //$session->remove('user');
        $session->clear();
        return new Response(json_encode([
            'status' => true,
            'message' => 'Успешный выход'
        ]));
    }
}
