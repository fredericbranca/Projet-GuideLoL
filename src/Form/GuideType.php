<?php

namespace App\Form;

use App\Entity\DataChampion;
use App\Entity\Guide;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GuideType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'required' => true,
                'attr' => [
                    'autocomplete' => 'off'
                ]
            ])
            ->add('voie', ChoiceType::class, [
                'required' => true,
                'choices' => [
                    'Mid' => 'Mid',
                    'Jungle' => 'Jungle',
                    'Top' => 'Top',
                    'Bottom' => 'Bottom',
                    'Support' => 'Support'
                ],
                'expanded' => true,
                'multiple' => false,
                'attr' => [
                    'autocomplete' => 'off'
                ],
            ])
            ->add('champion', ChoiceType::class, [
                'choices' => $options['champions'],
                'expanded' => true,
                'multiple' => false,
            ])

            // ->add('champion', TextType::class)
            ->add('Valider', SubmitType::class, [
                'label' => 'Publier le guide'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Guide::class,
            'champions' => []
        ]);
    }
}
