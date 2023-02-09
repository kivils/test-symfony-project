<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ViewController extends AbstractController
{
    #[Route('/view', name: 'app_view')]
    public function index(): Response
    {
        $date = date('l');

        $user = [
            'name' => 'Sergey',
            'surname' => 'Dovlatov',
            'age' => '98'
        ];

        $books = array(
            'Book 1',
            'Book 2',
            'Book 3',
            'Book 4',
        );

        return $this->render('view/index.html.twig', [
            'controller_name' => 'ViewController',
            'date' => $date,
            'user' => $user,
            'books' => $books
        ]);
    }
}
