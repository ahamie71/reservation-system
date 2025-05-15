<?php


namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Form\EventFilterType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted; // À ajouter


class EventController extends AbstractController
{
    #[Route('/events', name: 'event_index')]
    public function index(Request $request, EventRepository $eventRepository): Response
    {
        $filterForm = $this->createForm(EventFilterType::class);
        $filterForm->handleRequest($request);
    
        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $criteria = $filterForm->getData();
            $events = $eventRepository->findByFilter($criteria);
        } else {
            $events = $eventRepository->findAll();
        }
    
        return $this->render('event/index.html.twig', [
            'events' => $events,
            'filterForm' => $filterForm->createView(),
        ]);
    }


    #[Route('/event/new', name: 'event_new')]
#[IsGranted('ROLE_ADMIN')]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $event = new Event();
    $form = $this->createForm(EventType::class, $event);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Récupérer le fichier image depuis le formulaire
        $imageFile = $form->get('image')->getData();

        if ($imageFile) {
            // Générer un nom de fichier unique
            $newFilename = uniqid().'.'.$imageFile->guessExtension();

            // Déplacer le fichier dans le dossier public/images
            try {
                $imageFile->move(
                    $this->getParameter('uploads_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // gérer l’erreur si besoin
                $this->addFlash('danger', 'Erreur lors de l\'upload de l\'image.');
                return $this->redirectToRoute('event_new');
            }

            // Mettre à jour l’entité avec le nom du fichier
            $event->setImage($newFilename);
        }

        $entityManager->persist($event);
        $entityManager->flush();

        $this->addFlash('success', 'Événement créé avec succès !');

        return $this->redirectToRoute('event_index');
    }

    return $this->render('event/new.html.twig', [
        'form' => $form->createView(),
    ]);
}


// #[Route('/event/{id}/edit', name: 'event_edit')]
// #[IsGranted('ROLE_ADMIN')]
// public function edit(Request $request, Event $event, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
// {
//     $form = $this->createForm(EventType::class, $event);
//     $form->handleRequest($request);

//     if ($form->isSubmitted() && $form->isValid()) {
//         // Récupérer l'image soumise dans le formulaire
//         $imageFile = $form->get('imageFile')->getData();

//         if ($imageFile) {
//             // Générer un nom de fichier unique
//             $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
//             $safeFilename = $slugger->slug($originalFilename);
//             $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

//             // Déplacer le fichier vers le dossier /public/images
//             try {
//                 $imageFile->move(
//                     $this->getParameter('images_directory'), // ce paramètre doit être défini dans services.yaml
//                     $newFilename
//                 );
//             } catch (FileException $e) {
//                 // Gérer l'erreur si nécessaire
//                 throw new \Exception('Erreur lors de l’upload de l’image.');
//             }

//             // Mettre à jour le champ image de l’entité
//             $event->setImage($newFilename);
//         }

//         $entityManager->flush();

//         return $this->redirectToRoute('event_index');
//     }

//     return $this->render('event/edit.html.twig', [
//         'form' => $form->createView(),
//         'event' => $event,
//     ]);
// }


    #[Route('/event/{id}/delete', name: 'event_delete')]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Event $event, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($event);
        $entityManager->flush();

        return $this->redirectToRoute('event_index');
    }
}
