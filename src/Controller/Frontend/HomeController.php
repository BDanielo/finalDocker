<?php

namespace App\Controller\Frontend;

use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function __construct(
        private PostRepository $postRepo
    ) {
    }

    #[Route('', name: 'app.homepage')]
    public function index(): Response
    {
        return $this->render('Frontend/Home/index.html.twig', [
            'posts' => $this->postRepo->findLatestWithLimit(3),
        ]);
    }
}
