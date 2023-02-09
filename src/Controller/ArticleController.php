<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article')]
    public function index(Request $request): Response
    {
        $article = new Article();
        $article->setTitle('Title article');

        // Create doctrine manager
        $em = $this->getDoctrine()->getManager();
//        $em->persist($article);
//        $em->flush();

        $getArticle = $em->getRepository(Article::class)->findOneBy([
            'id'=>1
        ]);

        return $this->render('article/index.html.twig', [
            'article' => $getArticle
        ]);
    }
}
