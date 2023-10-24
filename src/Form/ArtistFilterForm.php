<?php

namespace App\Form;

use App\Entity\Category;
use App\Filter\SearchData;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArtistFilterForm extends AbstractType
{
    public function buildform(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('q', TextType::class,[
                'label'=>false,
                'required'=>false,
                'attr'=> [
                    'placeholder'=> 'Rechercher'
                ]
                ])
    
                ->add('category', EntityType::class,[
                    'class' => Category::class,
                    'choice_label' => 'name', 
                    'label' => 'Catégorie : ',
                    'placeholder' => 'Toutes les catégories', 
                    'required' => false, 
 
                ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults( [
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection'=> false
        ]);
    }

    public function getBlockPrefix()
    {
        return ''; //to clean the URL
    }
}