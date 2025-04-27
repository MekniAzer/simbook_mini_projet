<?php

// src/Form/LivresType.php
namespace App\Form;

use App\Entity\Livres;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivresType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // src/Form/LivresType.php
        $builder
            ->add('titre')
            ->add('slug')
            ->add('image')
            ->add('resume')
            ->add('editeur')
            ->add('dateEdition')
            ->add('prix') // Ensure this is included for the price field
            ->add('isbn')
            ->add('cat');

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Livres::class,
        ]);
    }
}
