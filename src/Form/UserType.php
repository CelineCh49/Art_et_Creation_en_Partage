<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('firstName',TextType::class,[
            'label'=> 'Prénom :',
            'required' => true,
        ])
        ->add('lastName',TextType::class,[
            'label'=> 'Nom :',
            'required' => true,
        ])
        ->add('telephone',TelType::class,[
            'label'=> 'Téléphone :',
            'required' => false,
            'attr' => [
                'pattern' => '/^0[1-9]([-. ]?[0-9]{2}){4}$/', 
                'title' => 'Un numéro de téléphone valide doit comporter 10 chiffres.'
            ]
        ])
        ->add('email',EmailType::class,[
            'label'=> 'Email :',
            'required' => true,
        ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les champs du mot de passe doivent correspondre.',
                'required' => false,  // <-- This makes it optional
                'mapped' => false,
                'first_options'  => ['label' => 'Mot de passe', 'required' => false],  // <-- Also here
                'second_options' => ['label' => 'Confirmer le mot de passe', 'required' => false],  // <-- And here
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le mot de passe doit contenir au minimum {{ limit }} caractères.',
                        'max' => 4000,
                        'maxMessage' => 'Le mot de passe doit contenir au maximum {{ limit }} caractères.',
                    ]),
                ],
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
