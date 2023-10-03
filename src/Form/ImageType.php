<?php

namespace App\Form;

use App\Entity\Artist;
use App\Entity\Event;
use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('image', FileType::class, [
            'label' => 'Télécharger une image (jpeg, png, webp - taille max: 1024K) ',

            // unmapped means that this field is not associated to any entity property
            'mapped' => false,

            // make it optional so you don't have to re-upload the PDF file
            // every time you edit the Product details
            'required' => false,

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
        ->add('artist', EntityType::class,[
            'class' => Artist::class,
            'choice_label' => 'artistName', 
            'label' => 'Artiste : ',
            'placeholder' => 'Sélectionnez l\'artiste', 
            'required' => false,
        ])
        ->add('event', EntityType::class,[
            'class' => Event::class,
            'choice_label' => 'name', 
            'label' => 'Evénement : ',
            'placeholder' => 'Sélectionnez l\'événement', 
            'required' => false,
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}
