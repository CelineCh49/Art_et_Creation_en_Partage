<?php

namespace App\Form;

use App\Entity\Artist;
use App\Entity\Event;
use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('fileName', TextType::class,[
            'label' => 'Nom du fichier : ', 
            'required' => true,
        ])
        ->add('title', TextType::class,[
            'label' => 'Titre de l\'image : ', 
        ])
        ->add('description', TextType::class,[
            'label' => 'Description de l\'image : ', 
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
