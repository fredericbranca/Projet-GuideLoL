<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class GuideFiltreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('champion', HiddenType::class, [
                'required' => false,
            ])
            ->add('role', ChoiceType::class, [
                'required' => false,
                'multiple' => false,
                'expanded' => true,
                'placeholder' => 'Tout',
                'choices' => [
                    'Mid' => 'Mid',
                    'Jungle' => 'Jungle',
                    'Top' => 'Top',
                    'Bottom' => 'Bottom',
                    'Support' => 'Support'
                ],
                'label' => 'Role'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
