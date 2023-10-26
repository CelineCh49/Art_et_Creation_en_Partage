<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\UserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    #[IsGranted("ROLE_ADMIN")]
    #[Route('/inscription', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UserAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $user->setRoles(["ROLE_USER"]);
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $errors = $this->checkUser($user);
            if (count($errors) === 0) {

                // encode the plain password
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );

                $entityManager->persist($user);
                $entityManager->flush();
                // do anything else you need here, like send an email

                return $userAuthenticator->authenticateUser(
                    $user,
                    $authenticator,
                    $request
                );
            } else {
                foreach ($errors as $error) {
                    $this->addFlash('error', $error);
                }
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    public function checkUser(User $user): array
    {
        $errors = [];
        //get data from event
        $email = $user->getEmail();
        $password = $user->getPassword();
        $firstName = $user->getFirstName();
        $lastName = $user->getLastName();

        //Check constraints
        if (!$email) {
            $errors[] = 'L\'email est obligatoire.';
        }
        if (!$password) {
            $errors[] = 'Le mot de passe est obligatoire.';
        }
        if (!$firstName) {
            $errors[] = 'Le prénom est obligatoire.';
        }
        if (!$lastName) {
            $errors[] = 'Le nom est obligatoire.';
        }

        return $errors;
    }
}
