<?php

namespace App\Controller;

use App\Message\SendEmailMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class DummyController extends AbstractController
{
    /**
     * @Route("/", name="app_dummy")
     */
    public function index(MailerInterface $mailer, MessageBusInterface $bus): Response
    {
        $email = (new Email())
            ->from('hello@example.com')
            ->to('you@example.com')
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $mailer->send($email);

        return $this->render('dummy/index.html.twig', [
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/BraveController.php',
        ]);
    }
}
