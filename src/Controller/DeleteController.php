<?php

namespace App\Controller;

use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class DeleteController extends AbstractController
{
    /**
     * Находим книжку по id и удаляем ее
     * id книги в базе данных, по нему находим нужную книгу
     * @param int $id
     * внедряем сервис Doctrine для удаления объекта
     * @param ManagerRegistry $managerRegistry
     * вовзращает редирект на домашнюю страницу
     * @return Response
     */
    #[Route('/delete/{id}', name: 'app_delete')]
    public function index(int $id, ManagerRegistry $managerRegistry): Response
    {
        //находим книгу
        $book = $managerRegistry
            ->getRepository(Book::class)
            ->findOneBy([
                'id' => $id
            ]);
        //удаляем ее
        $managerRegistry
            ->getRepository(Book::class)
            ->remove($book);
        return $this->redirectToRoute('home');//переходим на name home,т.е. главная страница
    }
}
