<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Artist;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
                'label'=> 'Nom de l\'événement :',
                'required' => true,
            ])
            ->add('openingDate',DateType::class ,[
                'label'=> 'Date d\'ouverture de l\'événenement',
                'html5'=>true,
                'widget'=>'single_text',
                'required'=>true
            ])
            ->add('closingDate',DateType::class ,[
                'label'=> 'Date de clôture de l\'événenement',
                'html5'=>true,
                'widget'=>'single_text',
                'required'=>true
            ])
            ->add('schedule',TextType::class,[
                'label'=> 'Créneau horaire :',
                'required' => true,
                'attr' => [
                    'placeholder' => 'ex: "de 10h à 12h et de 14h à 18h"'
                    ]  
            ])
            ->add('description', TextareaType::class,[
                'label'=> 'Description :',
                'required'=>false,
                'attr' => [
            'placeholder' => 'Décrivez l\'événement'
                ]   
            ])
            ->add('websiteLink',TextType::class,[
                'label'=> 'lien du site internet :',
                'required' => false,
                'attr' => [
                'placeholder' => 'http://www.exemple.com/'
                ]   
            ])
            ->add('facebookLink',TextType::class,[
                'label'=> 'lien du site facebook :',
                'required' => false,
                'attr' => [
                'placeholder' => 'https://www.facebook.com/exemple/'
                ]   
            ])
            ->add('instagramLink',TextType::class,[
                'label'=> 'lien du site instagram :',
                'required' => false,
                'attr' => [
                'placeholder' => 'https://www.instagram.com/exemple/'
                ]   
            ])
            ->add('artists', EntityType::class,[
                'class' => Artist::class,
                'choice_label' => 'artistName', 
                'label' => 'Artistes : ',
                'placeholder' => 'Sélectionnez les artistes', 
                'multiple' => true, // Enable multiple selections
                'required' => false,
                'by_reference' => false, // Important for changes to be applied
            ])
            ->add('favoriteImage', FileType::class, [
                'label' => 'Image favorite: ',
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
                'label' => 'Télécharger une image (jpeg, png, webp - taille max: 1024K) puis cliquer sur "Modifier" ',
    
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
            'data_class' => Event::class,
        ]);
    }
}
