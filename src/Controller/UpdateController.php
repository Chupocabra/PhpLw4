<?php

namespace App\Controller;

use App\Entity\Book;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class UpdateController extends AbstractController
{
    /**
     * Рендерим страничку с формой для изменения книжки
     * параметр id для определения книжки
     * @param int $id
     * внедряем сервис Doctrine для поиска книжки по id
     * @param ManagerRegistry $managerRegistry
     * рендер страницы для обновления книжки
     * @param Session $session
     * @return Response
     */
    #[Route('/update/{id}', name: 'app_update')]
    public function index(int $id, ManagerRegistry $managerRegistry, Session $session): Response
    {
        $user = $session->get('user');
        $book = $managerRegistry
            ->getRepository(Book::class)
            ->findOneBy([
                'id' => $id
            ]);
        //передадим туда юзера чтобы было понятно кто менял книжку
        return $this->render('update/index.html.twig', [
            'book' => $book,
            'user' => $user
        ]);
    }

    /**
     * Обновляем книгу
     * параметр id для определения книжки
     * @param int $id
     * для изменения книжки
     * @param ManagerRegistry $managerRegistry
     * возвращаем сообщение о результатах редактирования
     * @return Response
     */
    #[Route('/update/book/{id}', name: 'app_update_book')]
    public function update(int $id, ManagerRegistry $managerRegistry): Response
    {
        $book = $managerRegistry->getRepository(Book::class)
            ->findOneBy(['id' => $id]);

        $dateRead = new DateTime($_POST['readed']);
        $book->setName($_POST['name'])
             ->setAuthor($_POST['author'])
             ->setReadDate($dateRead);
        //установим 'null', это если файл/обложка были удалены
        if(isset($_POST['file'])){
            $book->setFile('null');
        }
        if(isset($_POST['cover'])){
            $book->setCover('null');
        }
        //если загружены новые файл/обложка, меняем на них
        if (isset($_FILES['cover'])) {
            $cover = 'uploads/' . uniqid() . $_FILES['cover']['name'];
            move_uploaded_file($_FILES['cover']['tmp_name'], $cover);
            $book->setCover($cover);
        }
        if (isset($_FILES['file'])) {
            $file = 'uploads/' . uniqid() . $_FILES['file']['name'];
            move_uploaded_file($_FILES['file']['tmp_name'], $file);
            $book->setFile($file);
        }

        $managerRegistry->getmanager()->persist($book);
        $managerRegistry->getmanager()->flush();

        return new Response(json_encode([
            'message' => 'Книга успешно отредактирована'
        ]));
    }
}
