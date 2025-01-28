<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class SecurityController extends AbstractController 
{


    public const SCOPES = [
        'google' => [],
    ];


    #[Route(path: "/login", name: 'auth_oauth_login', methods: ['GET'])]
    public function login(): Response {

        if ($this->getUser()) {
            return $this->redirectToRoute(route:'index');
        }

        return $this->render(view: "security/login.html.twig");

    }

    /**
     * @throws \Exception
     */

     #[Route(path:'/logout', name: 'auth_oauth_logout', methods: ['GET'])]
     public function logout() {

        //  throw new \Exception(message: 'Don\'t forget to activiate logout in security.yaml');
        unset($_SESSION['user']);

        return $this->redirectToRoute(route:'auth_oauth_login');
     }



    #[Route("/oauth/connect/{service}", name: 'auth_oauth_connect', methods: ['GET'])]
     public function connect(string $service, ClientRegistry $clientRegistry): RedirectResponse
     {
        if (! in_array($service, array_keys(array: self::SCOPES), strict: true)) {
            throw $this->createNotFoundException();
        }

        return $clientRegistry
            ->getClient($service)
            ->redirect(self::SCOPES[$service]);
     }

     #[Route('/oauth/check/{service}', name: 'auth_oauth_check', methods: ['GET', 'POST'])]
     public function check(): Response
     {
        return new Response(status: 200);
     }
}

