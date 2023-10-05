<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Artist;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
