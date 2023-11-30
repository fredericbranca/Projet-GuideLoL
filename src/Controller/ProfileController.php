<?php

namespace App\Controller;

use Imagick;
use App\Form\AvatarType;
use App\Form\ChangePseudoType;
use App\Repository\UserRepository;
use Google\Cloud\Storage\StorageClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileController extends AbstractController
{
    private $security;

    public function __construct(
        Security $security
    ) {
        $this->security = $security;
    }

    // Route pour afficher le profile de l'utilisateur et insertion du formulaire de changement de Pseudo
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

    #[Route('/profile/change-avatar', name: 'app_change_avatar', methods: ['POST'])]
    public function changeAvatar(Request $request, EntityManagerInterface $entityManager)
    {
        $user = $this->security->getUser();

        $form = $this->createForm(AvatarType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(['success' => true]);
            }

            $file = $form['avatar']->getData();

            // Nom de fichier unique
            $uniqueFileName = 'avatar_' . $user->getId() . '_' . time() . '.webp';

            // Chemin temporaire pour l'image téléchargée
            $tempFilePath = $file->getRealPath();

            // Convertir l'image en .webp
            $image = new Imagick($tempFilePath);
            $image->setImageFormat('webp');
            $newFilePath = sys_get_temp_dir() . '/' . $uniqueFileName;
            $image->writeImage($newFilePath);

            $entityManager->persist($user);
            $entityManager->flush();

            // Enregistre l'image sur Google Cloud Storage
            $storage = new StorageClient(['keyFilePath' => $_ENV['GOOGLE_APPLICATION_CREDENTIALS']]);
            $bucket = $storage->bucket('lol_guides');

            $bucket->upload(fopen($newFilePath, 'r'), [
                'name' => 'lol_guides_user_avatar/' . $uniqueFileName
            ]);

            // Mise à jour du nom de l'avatar dans la base de données
            $user->setAvatar($uniqueFileName);
            $entityManager->persist($user);
            $entityManager->flush();

            // Redirection + message
            $this->addFlash('success', 'Avatar mis à jour avec succès.');
            return $this->redirectToRoute('app_profile');

        } elseif ($form->isSubmitted() && !$form->isValid()) {

            if ($request->isXmlHttpRequest()) {
                // Renvoie les erreurs du formulaire pour la requête AJAX
                $errors = $this->getFormErrors($form);
                return new JsonResponse(['success' => false, 'errors' => $errors]);
            }

        }

        return $this->render('profile/change_avatar.html.twig', [
            'formAvatar' => $form->createView(),
        ]);
    }

    // Fonction pour récupérer les erreurs de formulaire
    private function getFormErrors($form)
    {
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }
        return $errors;
    }
}
