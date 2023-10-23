<?php

namespace App\Controller;

use App\Repository\ArtistRepository;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function main(EventRepository $eventRepository, ArtistRepository $artistRepository): Response
    {
        $events= $eventRepository->findAll();
        $artists= $artistRepository->findAll();

        // Random artists
        shuffle($artists); // Random 
        $randomArtists = array_slice($artists, 0, 4); // Select the first 4 artists 
       

        return $this->render('main/home.html.twig', [
            'events'=> $events,
            'artists' =>$artists,
            'randomArtists' => $randomArtists,
            'controller_name' => 'MainController',
        ]);
    }
}


//récup tous les id des artistes