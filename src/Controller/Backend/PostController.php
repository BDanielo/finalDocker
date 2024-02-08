<?php

namespace App\Controller\Backend;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/posts', name: 'admin.posts')]
class PostController extends AbstractController
{
    public function __construct(
        private PostRepository         $repo,
        private EntityManagerInterface $em
    ) {
    }

    #[Route('', name: '.index', methods: ['GET'])]
    public function index(): Response
    {
        // $posts = $this->repo->findAll();

        return $this->render('Backend/Post/index.html.twig', [
            'posts' => $this->repo->findAll(),
        ]);
    }

    #[Route('/{id}/update', name: '.update', methods: ['GET', 'POST'])]
    public function update(?Post $post, Request $request): Response|RedirectResponse
    {
        if (!$post instanceof Post) {
            $this->addFlash('error', 'Post non trouvé');

            return $this->redirectToRoute('admin.posts.index');
        }

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $post->setTitle(strip_tags($post->getTitle()));
            $post->setContent(strip_tags($post->getContent()));

            $this->em->persist($post);
            $this->em->flush();
            $this->addFlash('success', 'Post mis à jour avec succès');

            return $this->redirectToRoute('admin.posts.index');
        }

        return $this->render('Backend/Post/update.html.twig', [
            'form' => $form,
            'post' => $post,
        ]);
    }

    #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response|RedirectResponse
    {
        $post = new Post();
        $post->setEnable(true);
        $post->setIp($request->getClientIp());

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setAuthor($this->getUser());

            $post->setTitle(strip_tags($post->getTitle()));
            $post->setContent(strip_tags($post->getContent()));

            $this->em->persist($post);
            $this->em->flush();

            $this->addFlash('success', 'Post créé avec succès');

            return $this->redirectToRoute('admin.posts.index');
        }

        return $this->render('Backend/Post/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function delete(?Post $post, Request $request): RedirectResponse
    {
        if (!$post instanceof Post) {
            $this->addFlash('error', 'Post non trouvé');

            return $this->redirectToRoute('admin.posts.index');
        }

        if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->request->get('token'))) {
            $this->em->remove($post);
            $this->em->flush();
            $this->addFlash('success', 'Post supprimé avec succès');

            return $this->redirectToRoute('admin.posts.index');
        }

        $this->addFlash('error', 'Token CSRF invalide');

        return $this->redirectToRoute('admin.posts.index');
    }
}
