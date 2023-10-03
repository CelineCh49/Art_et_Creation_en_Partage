<?php

namespace App\Controller;

use App\Entity\ArtistImage;
use App\Form\ArtistImageType;
use App\Repository\ArtistImageRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/artist/image')]
class ArtistImageController extends AbstractController
{
    #[Route('/', name: 'app_artist_image_index', methods: ['GET'])]
    public function index(ArtistImageRepository $artistImageRepository): Response
    {
        return $this->render('artist_image/index.html.twig', [
            'artist_images' => $artistImageRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_artist_image_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,FileUploader $fileUploader): Response
    {
        $artistImage = new ArtistImage();
        $form = $this->createForm(ArtistImageType::class, $artistImage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();

            if ($file) {
            $uploadDirectory = "artists";
            $uploadFileName = $fileUploader->upload($file, $uploadDirectory );    
            $artistImage->setFileName($uploadFileName);
            }
            $entityManager->persist($artistImage);
            $entityManager->flush();

            return $this->redirectToRoute('app_artist_image_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('artist_image/new.html.twig', [
            'artist_image' => $artistImage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_artist_image_show', methods: ['GET'])]
    public function show(ArtistImage $artistImage): Response
    {
        return $this->render('artist_image/show.html.twig', [
            'artist_image' => $artistImage,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_artist_image_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ArtistImage $artistImage, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArtistImageType::class, $artistImage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_artist_image_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('artist_image/edit.html.twig', [
            'artist_image' => $artistImage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_artist_image_delete', methods: ['POST'])]
    public function delete(Request $request, ArtistImage $artistImage, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$artistImage->getId(), $request->request->get('_token'))) {
            $entityManager->remove($artistImage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_artist_image_index', [], Response::HTTP_SEE_OTHER);
    }
}
