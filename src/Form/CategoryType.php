<?php

namespace App\Form;

use App\Entity\Artist;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name',TextType::class,[
            'label'=> 'Nom de la catégorie : ',
            'required' => true,
        ])
            // ->add('artists', EntityType::class,[
            //     'class' => Artist::class,
            //     'choice_label' => 'artistName', 
            //     'label' => 'Artistes de cette catégorie',
            //     'placeholder' => 'Sélectionnez un ou plusieurs artistes', 
            //     'multiple' => true, // Enable multiple selections
            //     'required' => false, 
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
