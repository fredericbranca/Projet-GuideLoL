<?php

namespace App\Form;

use App\Entity\SortInvocateur;
use App\Entity\DataSortInvocateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class SortInvocateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('commentaire')
            ->add('ordre', HiddenType::class, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Une erreur s\'est produite.'
                    ])
                ],
                'invalid_message' => 'Une erreur s\'est produite.',
                'required' => true,
                'attr' => ['class' => 'ordre'],
            ])
            ->add('choixSortInvocateur', EntityType::class, [
                'class' => DataSortInvocateur::class,
                'multiple' => true,
                'expanded' => true,
                'invalid_message' => 'Sort d\'invocateur invalide',
                'constraints' => [
                    new Count([
                        'min' => 2,
                        'minMessage' => 'Vous devez sélectionner au moins 2 sorts d\'invocateur.'
                    ]),
                    new Count([
                        'max' => 2,
                        'maxMessage' => 'Vous devez sélectionner au maximum 2 sorts d\'invocateur.'
                    ])
                ],
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SortInvocateur::class,
        ]);
    }
}
