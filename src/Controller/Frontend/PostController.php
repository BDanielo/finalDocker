<?php

namespace App\Controller\Frontend;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/posts', name: 'app.posts')]
class PostController extends AbstractController
{
    public function __construct(
        private PostRepository $postRepo,
        private EntityManagerInterface $em
    ) {
    }

    #[Route('', name: '.index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('Frontend/Post/index.html.twig', [
            'posts' => $this->postRepo->findBy(['enable' => true]),
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

            return $this->redirectToRoute('app.posts.index');
        }

        return $this->render('Backend/Post/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/details/{slug}', name: '.show', methods: ['GET'])]
    public function show(?Post $post): Response
    {
        if (!$post instanceof Post) {
            $this->addFlash('error', 'Post non trouvé');

            return $this->redirectToRoute('app.posts.index');
        }

        return $this->render('Frontend/Post/show.html.twig', [
            'post' => $post
        ]);
    }
}
