<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class AuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function start(Request $request, AuthenticationException $authException = null): RedirectResponse
    {
        $code = $authException->getPrevious()->getCode();

        if ($code = 403) {
            $message = $authException->getPrevious()->getMessage();;

            if ($request->attributes->get('_route') === "note_guide") {
                $request->getSession()->getFlashBag()->add('note', $message);
                $id = $request->attributes->get('_route_params')['id'];

                return new RedirectResponse($this->urlGenerator->generate('get_guide_byId', ['id' => $id]));
            }
        }
        // add a custom flash message and redirect to the login page
        $request->getSession()->getFlashBag()->add('note', 'Vous devez vous connecter pour accéder à cette page.');

        return new RedirectResponse($this->urlGenerator->generate('app_login'));
    }
}
