<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DummyController extends AbstractController
{
    /**
     * @Route("/", name="app_dummy")
     */
    public function index(): Response
    {
        return $this->render('dummy/index.html.twig', [
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/BraveController.php',
        ]);
    }
}
