<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostFormType;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post')]
    public function index(PostRepository $postRepository, CategoryRepository $categoryRepository, SessionInterface $session): Response
    {
        $categorys = $categoryRepository->findAll();
        $session->set('categories', $categorys);
        $posts = $postRepository->findAll();
        dump($posts);
        return $this->render('post/index.html.twig', [
            'Articles' => $posts
        ]);
    }





    #[Route('/post/new', name: 'app_post_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $post = new Post();
        $form = $this->createForm(PostFormType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($post);
            $em->flush();
            return $this->redirectToRoute('app_post');
        }
        return $this->render('post/new.html.twig', [
            'formulaire' => $form->createView()
        ]);
    }


    #[Route('/post/{id}', name: 'post_show')]
    public function show(int $id, PostRepository $postRepository): Response
    {
        $posts = $postRepository->find($id);
        dump($posts);
        return $this->render('post/show.html.twig', [
            'article' => $posts
        ]);
    }
}
