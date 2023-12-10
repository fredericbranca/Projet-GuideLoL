<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function handle(Request $request, AccessDeniedException $accessDeniedException): RedirectResponse
    {
        $errorMessage = $accessDeniedException->getMessage();
        $request->getSession()->getFlashBag()->add('error', $errorMessage);

        if ($errorMessage === "Vous ne pouvez pas noter votre propre guide" || $errorMessage === "Vous avez déjà voté") {
            // Redirige erreur formulaire notation (depuis le Voter)
            $id = $request->attributes->get('_route_params')['id'];

            return new RedirectResponse($this->urlGenerator->generate('get_guide_byId', ['id' => $id]));
        }

        return new RedirectResponse($this->urlGenerator->generate('app_login'));
    }
}
