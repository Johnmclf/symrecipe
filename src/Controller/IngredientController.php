<?php

namespace App\Controller;

use App\Repository\IngredientRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Entity\Ingredient;
use App\Form\IngredientType;

final class IngredientController extends AbstractController
{
    #[Route('/ingredient', name: 'app_ingredient')]
    public function index(IngredientRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $ingredients =$paginator->paginate(
        $repository->findAll(),
          $request->query->getInt('page', 1),
          10
        );

        return $this->render('pages/ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    #[Route('/ingredient/nouveau','ingredient_new',methods:['GET','POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {

        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $ingredient = $form->getData();

            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash(
                'success',
                'Vos changements ont été enregistrés !'
            );

            return $this->redirectToRoute('app_ingredient');
        }
        
        return $this->render('pages/ingredient/new.html.twig',[ 'form' => $form, ]);
    }

    #[Route('/ingredient/edit/{id}','ingredient_edit',methods:['GET','POST'])]
    public function edit(Request $request, EntityManagerInterface $manager, Ingredient $ingredient): Response
    {
        $form = $this->createForm(IngredientType::class, $ingredient);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $ingredient = $form->getData();

            //$manager->persist($ingredient);
            $manager->flush();

            $this->addFlash(
                'success',
                'Vos changements ont été enregistrés !'
            );

            return $this->redirectToRoute('app_ingredient');
        }
        
        return $this->render('pages/ingredient/new.html.twig',[ 'form' => $form, ]);
    }

    #[Route('/ingredient/remove/{id}','ingredient_remove',methods:['GET','POST'])]
    public function remove(Request $request, EntityManagerInterface $manager, Ingredient $ingredient): Response
    {
        $manager->remove($ingredient);
        $manager->flush();
        
        $this->addFlash(
            'success',
            "L'ingrédient a été supprimé !"
        );
        return $this->redirectToRoute('app_ingredient');
    }

}
