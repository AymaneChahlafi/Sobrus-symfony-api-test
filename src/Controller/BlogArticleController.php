<?php

namespace App\Controller;

use App\Entity\BlogArticle;
use App\Form\BlogArticleType;
use App\Repository\BlogArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/blog/article')]
class BlogArticleController extends AbstractController
{
    #[Route('/', name: 'app_blog_article_index', methods: ['GET'])]
    public function index(BlogArticleRepository $blogArticleRepository): Response
    {
        return $this->render('blog_article/index.html.twig', [
            'blog_articles' => $blogArticleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_blog_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $blogArticle = new BlogArticle();
        $form = $this->createForm(BlogArticleType::class, $blogArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($blogArticle);
            $entityManager->flush();

            return $this->redirectToRoute('app_blog_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('blog_article/new.html.twig', [
            'blog_article' => $blogArticle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_blog_article_show', methods: ['GET'])]
    public function show(BlogArticle $blogArticle): Response
    {
        return $this->render('blog_article/show.html.twig', [
            'blog_article' => $blogArticle,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_blog_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BlogArticle $blogArticle, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BlogArticleType::class, $blogArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_blog_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('blog_article/edit.html.twig', [
            'blog_article' => $blogArticle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_blog_article_delete', methods: ['POST'])]
    public function delete(Request $request, BlogArticle $blogArticle, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$blogArticle->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($blogArticle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_blog_article_index', [], Response::HTTP_SEE_OTHER);
    }
}
