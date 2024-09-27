<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        dump($categories);
        return $this->render('category/index.html.twig', [
            'categories' => $categories
        ]);
    }



    #[Route('/category/new', name: 'app_category_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('app_category');
        }
        return $this->render('category/new.html.twig', [
            'formulaire' => $form->createView()
        ]);
    }
}
