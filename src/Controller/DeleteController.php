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
     * @param int $id
     * @param ManagerRegistry $managerRegistry
     * @return Response
     */
    #[Route('/delete/{id}', name: 'app_delete')]
    public function index(int $id, ManagerRegistry $managerRegistry): Response
    {
        $book = $managerRegistry
            ->getRepository(Book::class)
            ->findOneBy([
                'id' => $id
            ]);
        $managerRegistry
            ->getRepository(Book::class)
            ->remove($book);
        return $this->redirectToRoute('home');//переходим на name home,т.е. главная страница
    }
}
