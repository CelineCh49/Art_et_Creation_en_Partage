<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('email',EmailType::class,[
                'label'=> false,
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'L\'email est obligatoire',
                    ]),
                    
                ],
            ])
            // ->add('agreeTerms', CheckboxType::class, [
            //     'label'=> false,
            //     'mapped' => false,
            //     'required' => true,
            //     'constraints' => [
            //         new IsTrue([
            //             'message' => 'Vous devez accepter les conditions d\'utilisation',
            //         ]),
            //     ],
            // ])
            ->add('password', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'attr' => ['autocomplete' => 'new-password'],
                'label'=> false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Mot de passe obligatoire',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le mot de passe doit comporter au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('firstName',TextType::class,[
                'label'=> false,
                'required' => true,
            ])
            ->add('lastName',TextType::class,[
                'label'=> false,
                'required' => true,
            ])
            ->add('telephone',TelType::class,[
                'label'=> false,
                'required' => false,
                'attr' => [
                    'pattern' => '/^0[1-9]([-. ]?[0-9]{2}){4}$/', 
                    'title' => 'Un numéro de téléphone valide doit comporter 10 chiffres.'
                ]
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
