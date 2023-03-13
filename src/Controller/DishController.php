<?php

namespace App\Controller;

use App\Entity\Dish;
use App\Form\DishType;
use App\Repository\DishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dishes', name: 'dishes.')]
class DishController extends AbstractController
{
    #[Route('/', name: 'list')]
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
    public function create(Request $request): Response {
        $dish = new Dish();

        // Form
        $form = $this->createForm(DishType::class, $dish);
        $form->handleRequest($request);

        if($form->isSubmitted()) {
            // EntityManager
            $em = $this->getDoctrine()->getManager();
            $em->persist($dish);
            $em->flush();

            return $this->redirect($this->generateUrl('dishes.list'));
        }


        // Response
        return $this->render('dish/add.html.twig', [
            'addForm' => $form->createView(),
        ]);
    }

    /**
     * Delete dish
     * @param Request $request
     * @return Response
     */
    #[Route('/delete/{id}', name: 'delete')]
    public function delete($id, DishRepository $dishRepository):Response {
        // EntityManager
        $em = $this->getDoctrine()->getManager();
        $dish = $dishRepository->find($id);
        $em->remove($dish);
        $em->flush();

        // Message
        $this->addFlash('success', 'Dish was successfully removed');

        return $this->redirect($this->generateUrl('dishes.list'));
    }
}
