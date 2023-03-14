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

            // Get image from the form
            $image = $form->get('image')->getData();

            // Set unique file name
            if($image) {
                $filename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '-' . md5(uniqid()) . '.' . $image->guessExtension();
            }

            // Move image to a proper folder
            $image->move(
                $this->getParameter('images_folder'),
                $filename
            );

            // Update image in Dish object
            $dish->setImage($filename);

            // Update Dish and save to database
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
     * @param $id
     * @param DishRepository $dishRepository
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

    /**
     * Show dish page: Using ParamConverter: For simple cases with few data
     * @param Dish $dish
     * @return Response
     */
    #[Route('/{id}', name: 'show')]
    public function show(Dish $dish): Response {
        return $this->render('dish/detail.html.twig', [
            'dish' => $dish
        ]);
    }

    /**
     * Show dish page: Using DishRepository: For more complex cases with much data
     * @param $id
     * @param DishRepository $dishRepository
     * @return Response
     */
//    #[Route('/{id}', name: 'show')]
//    public function show($id, DishRepository $dishRepository): Response {
//        $dish = $dishRepository->find($id);
//
//        return $this->render('dish/detail.html.twig', [
//            'dish' => $dish
//        ]);
//    }
}
