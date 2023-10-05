<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\ArtistImage;
use App\Form\ArtistType;
use App\Repository\ArtistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/artist')]
class ArtistController extends AbstractController
{
    #[Route('/', name: 'app_artist_index', methods: ['GET'])]
    public function index(ArtistRepository $artistRepository): Response
    {
        return $this->render('artist/index.html.twig', [
            'artists' => $artistRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_artist_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $artist = new Artist();
        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $name = $form->get('artistName')->getData();
            $upperName = mb_strtoupper($name, 'UTF-8'); //Uppercase and accent
            $artist->setArtistName($upperName);

            // //Get images uploaded
            // $images=$form->get('images')->getData();

            // //Loop images
            //     //Generate a new unique filename
            //     foreach($images as $image){
            //     $file = md5(uniqid()).'.'.$image->guessExtension();
            //     //copy the file in uploads directory
            //     $image->move(
            //         $this->getParameter('images_directory'),
            //         $file
            //     );
            //     //Stock image in database
            //     $artistImage = new ArtistImage();
            //     $artistImage->setFileName($file);
            //     $artist->addArtistImage($artistImage);

            //     }
            //Images Management
            //Get images uploaded
            $image = $form->get('images')->getData();
            //return a string: temporary filename in the file Temp of the App
            if ($image) {
                //Generate a new unique filename
                $file = md5(uniqid()) . '.' . $image->guessExtension();

                //copy the file in uploads directory
                $image->move(
                    $this->getParameter('images_directory'),
                    $file
                );
                //Stock image in database
                $artistImage = new ArtistImage();
                $artistImage->setFileName($file);
                $artist->addArtistImage($artistImage);
            }

            $entityManager->persist($artist);
            $entityManager->flush();
            $this->addFlash('success', 'L\'artiste a été ajouté avec succès.');

            return $this->redirectToRoute('app_artist_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('artist/new.html.twig', [
            'artist' => $artist,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_artist_show', methods: ['GET'])]
    public function show(Artist $artist): Response
    {
        return $this->render('artist/show.html.twig', [
            'artist' => $artist,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_artist_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Artist $artist, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $name = $form->get('artistName')->getData();
            $upperName = mb_strtoupper($name, 'UTF-8');
            $artist->setArtistName($upperName);
            //Get images uploaded
            $image = $form->get('images')->getData();
            //return a string: temporary filename in the file Temp of the App
            if ($image) {
                //Generate a new unique filename
                $file = md5(uniqid()) . '.' . $image->guessExtension();

                //copy the file in uploads directory
                $image->move(
                    $this->getParameter('images_directory'),
                    $file
                );
                //Stock image in database
                $artistImage = new ArtistImage();
                $artistImage->setFileName($file);
                $artist->addArtistImage($artistImage);
                $entityManager->persist($artist);
                $this->addFlash('success', 'La photo a été ajoutée avec succès.');
            }

            $entityManager->persist($artist);
            $entityManager->flush();

            $this->addFlash('success', 'L\'artiste a été modifié avec succès.');

            return $this->render('artist/edit.html.twig', [
                'artist' => $artist,
                'form' => $form,
            ]);
        }

        return $this->render('artist/edit.html.twig', [
            'artist' => $artist,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_artist_delete', methods: ['POST'])]
    public function delete(Request $request, Artist $artist, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $artist->getId(), $request->request->get('_token'))) {
            $entityManager->remove($artist);
            $entityManager->flush();
            $this->addFlash('success', 'L\'artiste a été supprimé avec succès.');
        }

        return $this->redirectToRoute('app_artist_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/supprimer/image/{id}', name: 'app_artist_delete_image', methods: ['POST'])]
    public function deleteArtistImage(ArtistImage $artistImage, Request $request, EntityManagerInterface $entityManager, RequestStack $requestStack)
    { {
            if ($this->isCsrfTokenValid('delete' . $artistImage->getId(), $request->request->get('_token'))) {
                //Get image name
                $name = $artistImage->getFileName();
                //delete the file in the directory
                unlink($this->getParameter('images_directory') . '/' . $name);
                //delete in the database
                $entityManager->remove($artistImage);
                $entityManager->flush();
                $this->addFlash('success', 'L\'image a été supprimée avec succès.');

                // Get the URL of the page that called this method (edit page)
                $referer = $requestStack->getCurrentRequest()->headers->get('referer');

                // Redirect back to the edit page
                return $this->redirect($referer);
            }

            return $this->redirectToRoute('app_artist_index', [], Response::HTTP_SEE_OTHER);
        }




        // //AJAX request
        // $data = json_decode($request->getContent(),true); // true: to get column name

        // //check if token is valid
        // if ($this->isCsrfTokenValid('delete'.$artistImage->getId(), $data['_token'])){//token name : 'deleteId'
        //    //Get image name
        //     $name=$artistImage->getFileName();
        //     //delete the file in the directory
        //     unlink($this->getParameter('images_directory').'/'.$name);
        //     //delete in the database
        //     $entityManager->remove($artistImage);
        //     $entityManager->flush();

        //     //Json response
        //     return new JsonResponse(['success'=>1]);
        // }
        // else{
        //     return new JsonResponse(['error'=>'Token invalide'], 400);
        // }
    }
}
