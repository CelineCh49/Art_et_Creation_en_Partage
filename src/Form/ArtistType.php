<?php

namespace App\Form;

use App\Entity\Artist;
use App\Entity\Category;
use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ArtistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('artistName',TextType::class,[
            'label'=> 'Nom d\'artiste :',
            'required' => true,
            
        ])
        // ->add('firstName',TextType::class,[
        //     'label'=> 'Prénom :',
        //     'required' => true,
        // ])
        // ->add('lastName',TextType::class,[
        //     'label'=> 'Nom :',
        //     'required' => true,
        // ])
        ->add('description', TextareaType::class,[
            'label'=> 'Description :',
            'required' => true,
            'attr' => [
        'placeholder' => 'Décrivez votre style, votre concept, ce qui définit votre art...'
            ]   
        ])
        ->add('email',EmailType::class,[
            'label'=> 'Email :',
            'required' => false,
            
        ])
        ->add('telephone',TelType::class,[
            'label'=> 'Téléphone :',
            'required' => false,
            'attr' => [
                'pattern' => '/^0[1-9]([-. ]?[0-9]{2}){4}$/', 
                'title' => 'Un numéro de téléphone valide doit comporter 10 chiffres.'
            ],
            
        ])
        ->add('websiteLink',TextType::class,[
            'label'=> 'lien de votre site internet :',
            'required' => false,
            'attr' => [
            'placeholder' => 'http://www.exemple.com/'
            ]   
        ])
        ->add('facebookLink',TextType::class,[
            'label'=> 'lien de votre site Facebook :',
            'required' => false,
            'attr' => [
            'placeholder' => 'https://www.facebook.com/exemple/'
            ]   
        ])
        ->add('instagramLink',TextType::class,[
            'label'=> 'lien de votre site Instagram :',
            'required' => false,
            'attr' => [
            'placeholder' => 'https://www.instagram.com/exemple/'
            ]   
        ])
           // ->add('user')
           ->add('categories', EntityType::class,[
                'class' => Category::class,
                'choice_label' => 'name', 
                'label' => 'Catégories : ',
                'placeholder' => 'Sélectionnez une catégorie', 
                'required' => false, 
                'expanded' => true,//checkbox style
                'multiple' => true, // Enable multiple selections
                
                
            ])
            
            ->add('events', EntityType::class,[
                'class' => Event::class,
                'choice_label' => 'name', 
                'label' => 'Evénement participé',
                'placeholder' => 'Sélectionnez un ou plusieurs événements', 
                'expanded' => true,//checkbox style
                'multiple' => true, // Enable multiple selections
                'required' => false, 
            ])
            ->add('favoriteImage', FileType::class, [
                'label' => 'Image favorite: Télécharger une image (jpeg, png, webp - taille max: 1024K) puis cliquer sur "Valider" ',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Merci de télécharger une image au format valide',
                    ])
                ],
            ])
            ->add('images', FileType::class, [
                'label' => 'Télécharger une image (jpeg, png, webp - taille max: 1024K) puis cliquer sur "Valider" ',
    
                // unmapped means that this field is not associated to any entity property
                //So we can handle images ourself in ArtistController
               'mapped' => false,
    
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

              //  'multiple'=>true,
                  
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Merci de télécharger une image au format valide',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Artist::class,
        ]);
    }
}
