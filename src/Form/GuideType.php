<?php

namespace App\Form;

use App\Entity\DataChampion;
use App\Entity\Guide;
use App\Form\SortInvocateurType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
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

            ->add('champion', EntityType::class, [
                'class' => DataChampion::class,
                'multiple' => false,
                'expanded' => true,
                'invalid_message' => 'Champion invalide'
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

            ->add('Valider', SubmitType::class, [
                'label' => 'Publier le guide'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Guide::class,
        ]);
    }
}
