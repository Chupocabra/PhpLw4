<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;

class AuthorizationPhpController extends AbstractController
{
    /**
     * Рендерим страничку авторизации
     *
     * @return Response ответ функции - страница авторизации
     */
    #[Route('/authorization', name: 'app_authoriz')]
    public function index(): Response
    {
        $user = new User();
        return $this->render('authorization_php/index.html.twig', [
            'user' => $user
        ]);
    }
    /**
     * Заводим сессию, указываем в ней пользователя
     * внедряем сервис Doctrine - параметр для работы с базами данных
     * @param ManagerRegistry $doctrine
     * интерфейс сессий, при авторизации обновляем 'user' этой сессии
     * @param Session $session
     * в ответе сообщение с результатом авторизации
     * @return Response
     */
    #[Route('/authorization/login', name: 'app_authoriz_login')]
    public function login(ManagerRegistry $doctrine, Session $session): Response
    {
        //работа с бд
        $user = $doctrine
            ->getRepository(User::class)
            ->findOneBy([
                'email' => $_POST['email']
            ]);
        //не можем аавторизоваться
        if($user == null){
            return new Response(json_encode([
                'status' => false,
                'message' => 'Не найден user с таким email'
            ]));
        }

        $password=$_POST['pwd'];
        if($user->getPassword() != $password){
            return new Response(json_encode([
                'status' => false,
                'message' => 'Пароль введен неверно'
            ]));
        }
        else {
            $session->set('user', $user);
            return new Response(json_encode([
                'status' => true,
                'message' => 'Авторизация прошла успешно'
            ]));
        }
    }
}
