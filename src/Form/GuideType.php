<?php

namespace App\Form;

use App\Entity\Guide;
use App\Entity\DataChampion;
use Symfony\Component\Form\AbstractType;
use App\Validator\Constraints\AllowedVoies;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GuideType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Récupère de la liste des champions passée en option lors de la création du formulaire
        $champions = $options['champions'];

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
                'required' => true,
                'class' => DataChampion::class,
                'choices' => $champions
                ]
            )
            ->add('Valider', SubmitType::class, [
                'label' => 'Publier le guide'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Guide::class,
            'champions' => [] // initialisation de la liste dans un tableau vide pour éviter les erreurs
        ]);
    }
}