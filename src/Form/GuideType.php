<?php

namespace App\Form;

use App\Entity\Guide;
use App\Entity\DataChampion;
use Symfony\Component\Form\AbstractType;
use App\Validator\Constraints\AllowedVoies;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

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
                'constraints' => [
                    new AllowedVoies(['allowedVoies'=> ['Mid', 'Top', 'Jungle', 'Bottom', 'Support']])
                ]
            ])
            // ->add('champion', DataChampion::class, [
            //     'required' => true
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Guide::class,
        ]);
    }
}