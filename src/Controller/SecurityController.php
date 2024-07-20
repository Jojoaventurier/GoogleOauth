<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\SecurityController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class SecurityController extends AbstractController 
{
    #[Route('/login', name: 'login', methods: ['GET'])]
    public function login(): Response {

        if ($this->getUser()) {
            return $this->redirectToRoute('index');
        }

        return $this->render('security/login.html.twig');

    }



    
}

