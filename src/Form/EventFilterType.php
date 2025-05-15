<?php 

// src/Form/EventFilterType.php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class EventFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('startDate', DateType::class, [
            'required' => false,
            'widget' => 'single_text',
            'label' => 'Date min',
        ])
        ->add('endDate', DateType::class, [
            'required' => false,
            'widget' => 'single_text',
            'label' => 'Date max',
        ])
           ->add('name', TextType::class, [
                'required' => false,
                'label' => false,
            ])
            ->add('location', TextType::class, [
                'required' => false,
                'label' => false,
            ])
            ->add('minPrice', NumberType::class, [
                'required' => false,
                'label' => false,
            ])
            ->add('maxPrice', NumberType::class, [
                'required' => false,
                'label' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false, // optionnel si filtre GET
        ]);
    }
}


