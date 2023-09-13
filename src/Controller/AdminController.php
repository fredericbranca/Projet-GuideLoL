<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin/championUpdate')]
    public function championUpdate()
    {
        // $champions = json_decode(file_get_contents("..\src\DataFixtures\Champion\champions.json"), true);
        // foreach ($champions as $champion) {
            
        // }

        // return $this->render('admin/championUpdate.html.twig', [
        //     'champions' => $champions
        // ]);
    }

    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}
