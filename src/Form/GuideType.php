<?php

namespace App\Form;

use App\Entity\Guide;
use App\Entity\DataChampion;
use App\Form\CompetenceType;
use App\Form\SortInvocateurType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class GuideType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'constraints' => [
                    new Length([
                        'max' => 100,
                        'maxMessage' => '{{ limit }} caractères maximal'
                    ]),
                    new NotBlank([
                        'message' => 'Le titre ne peut pas être vide.',
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'Titre',
                    'autocomplete' => 'off'
                ],
                'required' => false,
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
                'invalid_message' => 'Choix invalide',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez choisir une voie.',
                    ]),
                ]
            ])

            ->add('champion', EntityType::class, [
                'class' => DataChampion::class,
                'required' => true,
                'multiple' => false,
                'expanded' => true,
                'invalid_message' => 'Champion invalide',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez choisir un champion.',
                    ]),
                ]
            ])

            ->add('groupeSortsInvocateur', CollectionType::class, [
                'entry_type' => SortInvocateurType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'attr' => ['class' => 'groupe-sorts-invocateur'],
                'required' => true,
            ])

            ->add('groupeRunes', CollectionType::class, [
                'entry_type' => RunesPageType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'attr' => ['class' => 'groupe-runes'],
                'required' => true,
            ])

            ->add('groupeEnsemblesItems', CollectionType::class, [
                'entry_type' => EnsembleGroupesItemsType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'attr' => ['class' => 'ensemble-groupes-items'],
                'required' => true,
            ])

            ->add('groupesCompetences', CollectionType::class, [
                'entry_type' => CompetenceType::class,
                'entry_options' => ['champion_id' => $options['champion_id']],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'attr' => ['class' => 'groupes-competences'],
                'required' => true,
            ])

            ->add('Valider', SubmitType::class, [
                'label' => 'Publier le guide'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Guide::class,
        ]);

        // Option champion_id
        $resolver->setDefined('champion_id');
    }
}
