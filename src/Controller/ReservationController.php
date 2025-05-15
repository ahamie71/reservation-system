<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationController extends AbstractController
{
    #[Route('/event/{id}/reserve', name: 'event_reserve')]
    #[IsGranted('ROLE_USER')] // L'utilisateur doit être connecté pour réserver
    public function reserve(Event $event, EntityManagerInterface $em): RedirectResponse
    {
        $user = $this->getUser();

        // Vérifier si l'utilisateur a déjà réservé cet événement
        $existingReservation = $em->getRepository(Reservation::class)->findOneBy([
            'user' => $user,
            'event' => $event,
        ]);

        if ($existingReservation) {
            $this->addFlash('warning', 'Vous avez déjà réservé cet événement.');
            return $this->redirectToRoute('event_index');
        }

        // Création de la réservation
        $reservation = new Reservation();
        $reservation->setUser($user);
        $reservation->setEvent($event);

        $em->persist($reservation);
        $em->flush();

        $this->addFlash('success', 'Réservation effectuée avec succès !');

        return $this->redirectToRoute('event_index');
    }



    #[Route('/mes-reservations', name: 'user_reservations')]
    #[IsGranted('ROLE_USER')]  // accès réservé aux utilisateurs connectés
    public function index(ReservationRepository $reservationRepository): Response
    {
        $user = $this->getUser();

        // Récupérer les réservations du user
        $reservations = $reservationRepository->findBy(['user' => $user]);

        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservations,
        ]);
    }
}

