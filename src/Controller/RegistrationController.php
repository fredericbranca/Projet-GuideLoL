<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        TokenStorageInterface $tokenStorage,
        SessionInterface $session
    ): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // pseudo : user + nombre généré en incrémentant le dernier utilisé
            $number = $userRepository->getIncrementedLastNumberUsed();
            $user->setPseudo('user' . $number);

            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation(
                'app_verify_email',
                $user,
                (new TemplatedEmail())
                    ->from(new Address('admin@example.com', 'GuidesLoL Mail bot'))
                    ->to($user->getEmail())
                    ->subject('Veuillez confirmer votre Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            // Authentication
            $token = new UsernamePasswordToken($user, 'main', $user->getRoles());
            $tokenStorage->setToken($token);
            $session->set('_security_main', serialize($token));

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, UserRepository $userRepository): Response
    {
        $id = $request->query->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_profile');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Votre adresse e-mail a été vérifiée.');

        return $this->redirectToRoute('app_home');
    }

    #[Route('/verify/newEmail', name: 'app_verify_newEmail')]
    public function verifyNewEmail(Security $security, SessionInterface $session)
    {
        $user = $security->getUser();

        if ($user->isVerified()) {
            throw new AccessDeniedException('Action non autorisé.');
        }

        $lastEmailSentTime = $session->get('last_email_sent_time');

        // Vérifier si le délai de refroidissement est écoulé (30mn)
        if ($lastEmailSentTime && (time() - $lastEmailSentTime) < 1800) {
            $tempsRestant = 1800 - (time() - $lastEmailSentTime);
            $minutesRestante = ceil($tempsRestant / 60);
            $this->addFlash('note', 'Veuillez patienter ' . $minutesRestante . ' minutes avant de renvoyer un email.');
            return $this->redirectToRoute('app_profile');
        }

        // generate a signed url and email it to the user
        $this->emailVerifier->sendEmailConfirmation(
            'app_verify_email',
            $user,
            (new TemplatedEmail())
                ->from(new Address('admin@example.com', 'GuidesLoL Mail bot'))
                ->to($user->getEmail())
                ->subject('Veuillez confirmer votre Email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
        );

        // Enregistrer l'heure actuelle comme dernière heure d'envoi
        $session->set('last_email_sent_time', time());

        // Ajouter un message de réussite
        $this->addFlash(
            'note', // Type du message (success, error, warning, etc.)
            'Un email de vérification a été envoyé. Veuillez vérifier votre boîte de réception.' // Message
        );

        return $this->redirectToRoute('app_profile');
    }
}
