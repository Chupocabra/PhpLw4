<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;

class CreateController extends AbstractController
{
    /**
     * Рендерим страничку для добавления книги
     * интерфейс сессии, для создания необходимо получить 'user'
     * @param Session $session
     * ответ функции - страница добавления
     * @return Response
     */
    #[Route('/create', name: 'app_create')]
    public function index(Session $session): Response
    {
        $user = $session->get('user');
        return $this->render('create/index.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * Добавляем книгу
     * менеджер сущностей, отвечает за сохранение в бд или полуцчение из бд
     * @param EntityManagerInterface $entityManager
     * интерфейс сессии, при добавлении книги в бд, записываем кто ее добавил
     * @param Session $session
     * отфет функции - сообщение о добавлении
     * @return Response
     */
    #[Route('/create/book', name: 'app_create_book')]
    public function create(EntityManagerInterface $entityManager, Session $session): Response
    {
        $book = new Book();
        if (isset($_FILES['cover'])) {
            $cover = 'uploads/' . uniqid() . $_FILES['cover']['name'];//путь
            move_uploaded_file($_FILES['cover']['tmp_name'], $cover);//перемещаем файлы
            $book->setCover($cover);//формируем для работы с бд
        }

        if (isset($_FILES['file'])) {
            $file = 'uploads/' . uniqid() . $_FILES['file']['name'];
            move_uploaded_file($_FILES['file']['tmp_name'], $file);
            $book->setFile($file);
        }
        else{
            $book->setFile("null");//это чтобы убрать кнопку читать в пустых файлах
        }

        $dateRead = new DateTime($_POST['date']);//меняем datetime для mysql
        $user = $session->get('user');

        $book->setName($_POST['name'])
             ->setAuthor($_POST['author'])
             ->setReadDate($dateRead)
             ->setAddedBy($user);


        $entityManager->merge($book);//объединяем изменения в запросе
        $entityManager->flush();//выполняем запрос

        return new Response(json_encode([//формируем ответ, статус и сообщение в алерт
            'status' => true,
            'message' => 'Книга добавлена'
        ]));
    }
}
