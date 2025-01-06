<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class DeveloperAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_developer_login';

    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');
        $password = $request->request->get('password', '');

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($password)
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Ajouter des données à la session
        $session = $request->getSession();
        $user = $token->getUser();
    
        // Vérifier si l'utilisateur est une instance de l'entité User
        if ($user instanceof \App\Entity\User) {
            $session->set('user_id', $user->getId());
        }

        // Redirection vers la page ciblée ou une autre route
        $targetPath = $this->getTargetPath($session, $firewallName);

        // $targetPath = $this->getTargetPath($request->getSession(), $firewallName);

        if ($targetPath) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->router->generate('app_developer'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->router->generate(self::LOGIN_ROUTE);
    }
}
