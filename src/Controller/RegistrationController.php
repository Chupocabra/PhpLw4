<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManagerInterface;

class RegistrationController extends AbstractController
{
    /**
     * Рендерим страничку с формой регистрации
     * @param Session $session
     * @return Response
     */
    #[Route('/registration', name: 'app_registration')]
    public function index(Session $session): Response
    {
        $user = new User();
        return $this->render('registration/index.html.twig', [
            'user' => $user
        ]);
    }
    /**
     * Регистрация
     * @return Response
     * @param Session $session
     * @param EntityManagerInterface $entityManager
     */
    #[Route('/registration/send', name: 'app_registration_send')]
    public function registr(EntityManagerInterface $entityManager, Session $session): Response
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $pwd = $_POST['pwd'];

        $findEmail = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy([
                'email' => $email
            ]);
        //сверяем с email,он должен быть уникальным
        if($findEmail){
            return new Response(json_encode([
                'message' => 'Такой email уже зарегистрирован',
                'status' => false
            ]));
        }
        else {
            $user = new User();
            $user->setName($name)
                 ->setEmail($email)
                 ->setPassword($pwd);
            $entityManager->persist($user);//готовим запрос
            $entityManager->flush();//и выполняем его

            $session->set('user', $user);//теперь мы -- зарегистрировавшийся user
            return new Response(json_encode([
                    'message' => 'Регистрация прошла успешно',
                    'status' => true
                ]));
        }
    }
}
