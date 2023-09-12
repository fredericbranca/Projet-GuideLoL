<?php

namespace App\Controller;

use App\Entity\Guide;
use App\Form\GuideType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GuideController extends AbstractController
{
    // Route qui mène vers la création d'un guide et redirige vers le guide créé s'il est validé
    #[Route('/guide/new', name: 'new_guide')]
    public function new(Request $request, EntityManagerInterface $entityManager) {

        // Création du formulaire avec le GuideType
        $form = $this->createForm(GuideType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $guide = new Guide();
            $guide = $form->getData();
            $entityManager->persist($guide);
            $entityManager->flush();

            // return $this->redirectToRoute('infos_guide', ['id' => $guide->getId()]);
        }

        return $this->render('guide/create_guide.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/guide', name: 'app_guide')]
    public function index(): Response
    {
        return $this->render('guide/index.html.twig', [
            'controller_name' => 'GuideController',
        ]);
    }

}
