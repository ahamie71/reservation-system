<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('default/home.html.twig', [
            'app_name' => 'Reservation System',
            'app_description' => 'Une application simple pour gérer les événements et les réservations.',
        ]);
    }
}
