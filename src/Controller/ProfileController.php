<?php

namespace App\Controller;

use App\Form\ChangePseudoType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProfileController extends AbstractController
{
    private $security;

    public function __construct(
        Security $security
    ) {
        $this->security = $security;
    }

    #[Route('/profile', name: 'app_profile')]
    public function index(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $user = $this->security->getUser();

        // Création du formulaire de changement de pseudo
        $changePseudoForm = $this->createForm(ChangePseudoType::class);
        $changePseudoForm->handleRequest($request);

        if ($changePseudoForm->isSubmitted() && $changePseudoForm->isValid()) {
            // Récupérer les données du formulaire
            $formData = $changePseudoForm->getData();
            $newPseudo = $formData['newPseudo'];

            // Convertir le nouveau pseudo en minuscules pour la vérification
            $newPseudoLower = strtolower($newPseudo);

            // Vérifie si le pseudo est déjà pris
            $existingUser = $userRepository->findOneByLowercasePseudo($newPseudoLower);
            if ($existingUser) {
                if ($existingUser == $user) {
                    $this->addFlash('note', 'Ce pseudo est déjà utilisé par vous-mêmes.');
                    return $this->redirectToRoute('app_profile');
                }
                $this->addFlash('error', 'Ce pseudo est déjà utilisé par un autre utilisateur.');
                return $this->redirectToRoute('app_profile');
            }

            // Mettre à jour le pseudo de l'utilisateur
            $user->setPseudo($newPseudo);

            // Sauvegarde les changements en base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Ajouter un message flash de succès
            $this->addFlash('success', 'Votre pseudo a été mis à jour avec succès.');

            // Redirige vers le profile
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'changePseudoForm' => $changePseudoForm
        ]);
    }
}
