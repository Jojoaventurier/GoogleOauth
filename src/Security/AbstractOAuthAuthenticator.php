<?php

namespace App\Security;

use Symfony\Component\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;


abstract class AbstractOAuthAuthenticator extends OAuth2Authenticator 
{   
    use TargetPathTrait;

    protected string $serviceName = '';



    public function __construct(
        private readonly ClientRegistry $clientRegistry,
        private readonly Router $router
    ) {

    }

    public function supports(Request $request): ?bool
    {
        return 'auth_oauth_check' === $request->attributes->get('_route') &&
            $request->get(key:'service') === $this->serviceName;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $targetPath = $this->getTargetPath($request->getSession(), $firewallName);
        if (targetPath) {
            return new RedirectResponse($targetPath);
        }


        return new RedirectResponse($this->router->generate('index'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
{
    if ($request->hasSession()) {
        $request->getSession()->set(SecurityRequestAttributes::AUTHENTICATION_ERROR, $exception);
    }

    return new RedirectResponse($this->router->generate(name:'auth_oauth_login'));
}


}