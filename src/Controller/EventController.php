<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\EventImage;
use App\Form\EventType;
use App\Repository\ArtistRepository;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/event')]
class EventController extends AbstractController
{
    #[Route('/', name: 'app_event_index', methods: ['GET'])]
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('event/index.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }

    #[IsGranted("ROLE_ADMIN")]
    #[Route('/new', name: 'app_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ArtistRepository $artistRepository): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $errors = $this->checkEvent($event);
            if (count($errors) === 0) {

                //Favorite Image
                //Get images uploaded
                $favoriteImage = $form->get('favoriteImage')->getData();
                //return a string: temporary filename in the file Temp of the App
                if ($favoriteImage) {
                    //Generate a new unique filename
                    $file = md5(uniqid()) . '.' . $favoriteImage->guessExtension();

                    //copy the file in uploads directory
                    $favoriteImage->move(
                        $this->getParameter('event_images_directory'),
                        $file
                    );
                    //Stock image in database
                    $event->setFavoriteImage($file);
                } else {
                    // default image
                    $defaultImage = 'default.png';
                    $event->setFavoriteImage($defaultImage);
                }

                //Images Management
                //Get images uploaded
                $image = $form->get('images')->getData();
                //return a string: temporary filename in the file Temp of the App
                if ($image) {
                    //Generate a new unique filename
                    $file = md5(uniqid()) . '.' . $image->guessExtension();

                    //copy the file in uploads directory
                    $image->move(
                        $this->getParameter('event_images_directory'),
                        $file
                    );
                    //Stock image in database
                    $eventImage = new EventImage();
                    $eventImage->setFileName($file);
                    $event->addEventImage($eventImage);
                }

                // $formArtists = $form->get('artists')->getData();
                // foreach ($formArtists as $formArtist) {
                //     $formArtistName = $formArtist->getArtistName();
                //     $artist = $artistRepository->findOneByArtistName($formArtistName);
                //     $artist->addEvent($event);
                //     $entityManager->persist($artist);
                // }
                $entityManager->persist($event);
                $entityManager->flush();
                $this->addFlash('success', 'L\'événement a été ajouté avec succès.');

                return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
            } else {
                foreach ($errors as $error) {
                    $this->addFlash('error', $error);
                }
            }
        }

        return $this->render('event/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_event_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[IsGranted("ROLE_ADMIN")]
    #[Route('/{id}/edit', name: 'app_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EntityManagerInterface $entityManager, ArtistRepository $artistRepository, EventRepository $eventRepository): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $errors = $this->checkEvent($event);
            if (count($errors) === 0) {

                //Favorite Image
                //Get images uploaded
                $favoriteImage = $form->get('favoriteImage')->getData();
                //return a string: temporary filename in the file Temp of the App
                if ($favoriteImage) {
                    //Generate a new unique filename
                    $file = md5(uniqid()) . '.' . $favoriteImage->guessExtension();

                    //copy the file in uploads directory
                    $favoriteImage->move(
                        $this->getParameter('event_images_directory'),
                        $file
                    );
                    //Stock image in database
                    $event->setFavoriteImage($file);
                }

                //Images Management
                //Get images uploaded
                $image = $form->get('images')->getData();
                //return a string: temporary filename in the file Temp of the App
                if ($image) {
                    //Generate a new unique filename
                    $file = md5(uniqid()) . '.' . $image->guessExtension();

                    //copy the file in uploads directory
                    $image->move(
                        $this->getParameter('event_images_directory'),
                        $file
                    );
                    //Stock image in database
                    $eventImage = new EventImage();
                    $eventImage->setFileName($file);
                    $event->addEventImage($eventImage);
                    $entityManager->persist($event);
                    $this->addFlash('success', 'La photo a été ajoutée avec succès.');
                }

                $entityManager->persist($event);
                $entityManager->flush();
                $this->addFlash('success', 'L\'événement a été modifié avec succès.');

                return $this->render('event/edit.html.twig', [
                    'event' => $event,
                    'form' => $form,
                ]);
            }else {
                foreach ($errors as $error) {
                    $this->addFlash('error', $error);
                }
            }
        }

        return $this->render('event/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_event_delete', methods: ['POST'])]
    public function delete(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $event->getId(), $request->request->get('_token'))) {
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/supprimer/image/{id}', name: 'app_event_delete_image', methods: ['POST'])]
    public function deleteEventImage(EventImage $eventImage, Request $request, EntityManagerInterface $entityManager, RequestStack $requestStack)
    { {
            if ($this->isCsrfTokenValid('delete' . $eventImage->getId(), $request->request->get('_token'))) {
                //Get image name
                $name = $eventImage->getFileName();
                //delete the file in the directory
                unlink($this->getParameter('event_images_directory') . '/' . $name);
                //delete in the database
                $entityManager->remove($eventImage);
                $entityManager->flush();
                $this->addFlash('success', 'L\'image a été supprimée avec succès.');

                // Get the URL of the page that called this method (edit page)
                $referer = $requestStack->getCurrentRequest()->headers->get('referer');

                // Redirect back to the edit page
                return $this->redirect($referer);
            }

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER); //TODO: ajouter page erreur: vous n'avez pas accès
        }
    }


    public function checkEvent(Event $event): array
    {
        $errors = [];
        //get data from event
        $name = $event->getName();
        $openingDate = $event->getOpeningDate();
        $closingDate = $event->getClosingDate();
        $schedule = $event->getSchedule();

        //Check constraints
        if (!$name) {
            $errors[] = 'Le nom est obligatoire.';
        }
        if ($closingDate < $openingDate) {
            $errors[] = 'La date de clôture doit être postérieure à la date d\'ouverture.'; // TODO:à modifier, ne marche pas
        }
        if (!$schedule) {
            $errors[] = 'Les horaires sont obligatoires.';
        }

        return $errors;
    }
}
