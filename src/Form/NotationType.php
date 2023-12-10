<?php

namespace App\Form;

use App\Entity\Evaluation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class NotationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('notation', ChoiceType::class, [
                'label' => 'Notez ce guide',
                'required' => true,
                'invalid_message' => 'Une erreur s\'est produite.',
                'multiple' => false,
                'expanded' => false,
                'choices' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                    '6' => 6,
                    '7' => 7,
                    '8' => 8,
                    '9' => 9,
                    '10' => 10,
                ],
                'placeholder' => 'Note',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez sélectionner une note',
                    ]),
                    new Choice([
                        'choices' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                        'message' => 'Veuillez sélectionner une note valide',
                    ]),
                ]
            ])
            ->add('submit', SubmitType::class, ['label' => 'Noter']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Evaluation::class,
        ]);
    }
}
