<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\ArtistImage;
use App\Filter\SearchData;
use App\Form\ArtistFilterForm;
use App\Form\ArtistType;
use App\Repository\ArtistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

use function PHPUnit\Framework\isEmpty;

#[Route('/artist')]
class ArtistController extends AbstractController
{
    #[Route('/', name: 'app_artist_index', methods: ['GET'])]
    public function index(ArtistRepository $artistRepository, Request $request): Response
    {
        $data = new SearchData();
        $form = $this->createForm(ArtistFilterForm::class, $data);
        $form->handleRequest($request);
        $artists = $artistRepository->findSearch($data);
        return $this->render('artist/index.html.twig', [
            'artists' => $artists,
            'form' => $form->createView()
        ]);
    }

    #[IsGranted("ROLE_USER")]
    #[Route('/new', name: 'app_artist_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if ($user->getArtist() !== null) {
            // L'utilisateur a déjà un artiste, donc vous pouvez renvoyer une erreur ou rediriger
            throw new AccessDeniedException('Vous avez déjà créé une fiche artiste. Vous ne pouvez pas en posséder plusieurs.');
        }
        //si $user contains artist -->message refus: un utkilisateur ne peut créer qu'une fiche artiste-->renvoi accueil
        //sinon
        $artist = new Artist();
        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $errors = $this->checkArtist($artist);
            if (count($errors) === 0) {

                //ArtisteName to uppercase
                $name = $form->get('artistName')->getData(); 
                $upperName = ucfirst($name);
                $artist->setArtistName($upperName);
                $artist->setUser($user);

                // //Get images uploaded
                // $images=$form->get('images')->getData();

                // //Loop images
                //     //Generate a new unique filename
                //     foreach($images as $image){
                //     $file = md5(uniqid()).'.'.$image->guessExtension();
                //     //copy the file in uploads directory
                //     $image->move(
                //         $this->getParameter('artist_images_directory'),
                //         $file
                //     );
                //     //Stock image in database
                //     $artistImage = new ArtistImage();
                //     $artistImage->setFileName($file);
                //     $artist->addArtistImage($artistImage);

                //     }

                //Favorite Image
                //Get images uploaded
                $favoriteImage = $form->get('favoriteImage')->getData();
                //return a string: temporary filename in the file Temp of the App
                if ($favoriteImage) {
                    //Generate a new unique filename
                    $file = md5(uniqid()) . '.' . $favoriteImage->guessExtension();

                    //copy the file in uploads directory
                    $favoriteImage->move(
                        $this->getParameter('artist_images_directory'),
                        $file
                    );
                    //Stock image in database
                    $artist->setFavoriteImage($file);
                } else {
                    // default image
                    $defaultImage = 'default.png';
                    $artist->setFavoriteImage($defaultImage);
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
                        $this->getParameter('artist_images_directory'),
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
            }else{
                foreach ($errors as $error) {
                    $this->addFlash('error', $error);
                }
            }
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

    #[IsGranted("ROLE_USER")]
    #[Route('/{id}/edit', name: 'app_artist_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Artist $artist, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if ($artist->getUser() == $user || $this->isGranted("ROLE_ADMIN")) {
            $form = $this->createForm(ArtistType::class, $artist);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $errors = $this->checkArtist($artist);
                if (count($errors) === 0) {

                    //ArtisteName to uppercase
                    $name = $form->get('artistName')->getData();
                    $upperName = ucfirst($name);
                    $artist->setArtistName($upperName);

                    //Favorite Image
                    //Get images uploaded
                    $favoriteImage = $form->get('favoriteImage')->getData();
                    //return a string: temporary filename in the file Temp of the App
                    if ($favoriteImage) {
                        //Generate a new unique filename
                        $file = md5(uniqid()) . '.' . $favoriteImage->guessExtension();

                        //copy the file in uploads directory
                        $favoriteImage->move(
                            $this->getParameter('artist_images_directory'),
                            $file
                        );
                        //Stock image in database
                        $artist->setFavoriteImage($file);
                    }
                    //Gallery
                    //Get images uploaded
                    $image = $form->get('images')->getData();
                    //return a string: temporary filename in the file Temp of the App
                    if ($image) {
                        //Generate a new unique filename
                        $file = md5(uniqid()) . '.' . $image->guessExtension();

                        //copy the file in uploads directory
                        $image->move(
                            $this->getParameter('artist_images_directory'),
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
                } else {
                    foreach ($errors as $error) {
                        $this->addFlash('error', $error);
                    }
                }
            } 
            return $this->render('artist/edit.html.twig', [
                'artist' => $artist,
                'form' => $form,
            ]);
        } else {
            throw new AccessDeniedException('Accès interdit');
        }
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
                unlink($this->getParameter('artist_images_directory') . '/' . $name);
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
    }



    // //AJAX request
    // $data = json_decode($request->getContent(),true); // true: to get column name

    // //check if token is valid
    // if ($this->isCsrfTokenValid('delete'.$artistImage->getId(), $data['_token'])){//token name : 'deleteId'
    //    //Get image name
    //     $name=$artistImage->getFileName();
    //     //delete the file in the directory
    //     unlink($this->getParameter('artist_images_directory').'/'.$name);
    //     //delete in the database
    //     $entityManager->remove($artistImage);
    //     $entityManager->flush();

    //     //Json response
    //     return new JsonResponse(['success'=>1]);
    // }
    // else{
    //     return new JsonResponse(['error'=>'Token invalide'], 400);
    // }

    public function checkArtist(Artist $artist): array
    {
        $errors = [];
        //get data from artist
        $artistName = $artist->getArtistName();
        $description = $artist->getDescription();
        $categories = $artist->getCategories();


        //Check constraints
        if (!$artistName) {
            $errors[] = 'Le nom d\'artiste est obligatoire.';
        }
        if (!$description) {
            $errors[] = 'La description est obligatoire.';
        }
       
        return $errors;
    }
}
