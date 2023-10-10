<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/user')]
class UserController extends AbstractController
{
 // Ajout du constructeur pour le password hasher pour pouvoir éditer le profil
 private UserPasswordHasherInterface $passwordHasher;

 public function __construct(UserPasswordHasherInterface $passwordHasher)
 {
     $this->passwordHasher=$passwordHasher;

 }

    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[IsGranted("ROLE_USER")]
    #[Route('/my_profile', name: 'my_profile')]
    public function afficherMonProfil(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser(); // Récupère l'utilisateur actuellement connecté

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                
            $user = $form->getData();

            // Si le mot de passe est modifié
            if ($form->get('password')->getData()) {
                $user->setPassword($this->passwordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                ));
            }
            
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Profil mis à jour avec succès!');

            // Redirection vers le même route pour voir les modifications
            return $this->redirectToRoute('my_profile', [
                'id'=>$user->getId()
            ]);
        }else{
            // Si le formulaire n'est pas soumis, on rafraichit l'objet participant pour éviter les erreurs
            $entityManager->refresh($user);
        }

        return $this->render('user/my_profile.html.twig', [
            'form' => $form->createView(),
            'user'=>$user,
        ]);
    }

    // #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $user = new User();
    //     $form = $this->createForm(UserType::class, $user);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->persist($user);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('user/new.html.twig', [
    //         'user' => $user,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    // public function show(User $user): Response
    // {
    //     return $this->render('user/show.html.twig', [
    //         'user' => $user,
    //     ]);
    // }

    // #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(UserType::class, $user);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('user/edit.html.twig', [
    //         'user' => $user,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    // public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
    //         $entityManager->remove($user);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    // }
}
