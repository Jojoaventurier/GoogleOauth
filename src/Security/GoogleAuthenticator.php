<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\AbstractOAuthAuthenticator;
use League\OAuth2\Client\Provider\GoogleUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use Symfony\Component\Messenger\Exception\RuntimeException;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class GoogleAuthenticator extends AbstractOAuthAuthenticator
{


    protected string $serviceName ='google';


    protected function getUserFromResourceOwner(ResourceOwnerInterface $resourceOwner, UserRepository $repository): ?User
    {
        if (!($resourceOwner instanceof GoogleUser)) {
            throw new RuntimeException(message:"expecting Google User");
        }

        if(true !==  ($resourceOwner->toArray()['email_verified'] ?? null)) {
            throw new AuthenticationException(message:"email not verified");
        }

        return $repository->findOneBy([
            'google_id' => $resourceOwner->getId(),
            'email' => $resourceOwner->getEmail()
        ]);
    }





}
