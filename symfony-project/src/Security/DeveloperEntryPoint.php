<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DeveloperEntryPoint implements AuthenticationEntryPointInterface
{
    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return new RedirectResponse('/developer-login');
    }


    // public function __construct(
    //     private UrlGeneratorInterface $urlGenerator,
    // ) {
    // }

    // public function start(Request $request, ?AuthenticationException $authException = null): RedirectResponse
    // {
    //     // add a custom flash message and redirect to the login page
    //     $request->getSession()->getFlashBag()->add('note', 'You have to login in order to access this page.');

    //     return new RedirectResponse($this->urlGenerator->generate('security_login'));
    // }
}
