<?php

namespace App\Controller;

use App\Entity\Dish;
use App\Repository\DishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dishes', name: 'dishes.')]
class DishController extends AbstractController
{
    #[Route('/', name: 'edit')]
    public function index(DishRepository $dishRepository): Response
    {
        $dishes = $dishRepository->findAll();
        return $this->render('dish/index.html.twig', [
            'dishes' => $dishes,
        ]);
    }

    /**
     * Create new Dish
     * @param Request $request
     * @return void
     */
    #[Route('/add', name: 'add')]
    public function create(Request $request) {
        $dish = new Dish();
        $dish->setTitle('Pizza');
        $dish->setDescription('Pizza description');
        $dish->setPrice(16);

        // EntityManager
        $em = $this->getDoctrine()->getManager();
        $em->persist($dish);
        $em->flush();

        // Response
        return new Response('New dish was added');
    }
}
