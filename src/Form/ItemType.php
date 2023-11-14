<?php

namespace App\Form;

use App\Entity\DataItem;
use App\Entity\ItemsGroup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('commentaire')
            ->add('ordre', IntegerType::class, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Une erreur s\'est produite.'
                    ])
                ],
                'invalid_message' => 'Une erreur s\'est produite.',
                'required' => true,
                'attr' => [
                    'class' => 'ordre-groupe-items',
                    'style' => 'display: none;'
                ],
            ])
            ->add('choixItems', EntityType::class, [
                'class' => DataItem::class,
                'multiple' => true,
                'expanded' => true,
                'invalid_message' => 'Item invalide',
                'constraints' => [
                    new Count([
                        'max' => 10,
                        'maxMessage' => 'Vous pouvez sélectionner au maximum 10 items.'
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ItemsGroup::class,
        ]);
    }
}
