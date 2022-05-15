<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;

class HomePage extends AbstractController
{
    /**
     * Рендерим главную страничку
     * @Route("/", name="home")
     *
     * @param BookRepository $bookRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $session = new Session();//заводим сессию
        $session->start();
        //находим книжки и сортируем по новизне даты прочтения
        $books = $this->getDoctrine()
            ->getRepository(Book::class)
            ->findBy([], ['readDate' => 'DESC']);

        $currentPage = $request->query->getInt('page', 1);
        //проверим есть ли еще страничка
        $hasNext=true;
        if((count($books)-3*($currentPage))<=0){
            $hasNext=false;
        }
        //пагинация записей
        $books = $paginator->paginate(
            $books,//запрос
            $currentPage,//страничка
            3//число записей
        );

        $user = $session->get('user');

        return $this->render('home/index.html.twig',
            [
                'books' => $books,
                'currentPage' => $currentPage,
                'hasNext' => $hasNext,
                'user' => $user
            ]);
    }

}