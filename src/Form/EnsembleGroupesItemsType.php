<?php

namespace App\Form;

use App\Entity\EnsembleItemsGroups;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class EnsembleGroupesItemsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'constraints' => [
                    new Length([
                        'max' => 50,
                        'maxMessage' => '{{ limit }} caractères maximal'
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'Titre',
                    'autocomplete' => 'off'
                ],
                'required' => false
            ])
            ->add('ordre', IntegerType::class, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Une erreur s\'est produite.'
                    ])
                ],
                'invalid_message' => 'Une erreur s\'est produite.',
                'required' => true,
                'attr' => [
                    'class' => 'ordre',
                    'style' => 'display: none;'
                ],
            ])
            ->add('associationsEnsemblesItemsGroups', CollectionType::class, [
                'entry_type' => ItemType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'attr' => ['class' => 'groupe-items'],
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EnsembleItemsGroups::class,
        ]);
    }
}
