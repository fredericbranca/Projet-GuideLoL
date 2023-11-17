<?php

namespace App\Form;

use App\Entity\DataItem;
use App\Entity\ItemsGroup;
use App\Form\ChoixItemsType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9@()$!%*?&,éèàù#\[\]]*$/',
                        'message' => 'Caractères spéciaux autorisés ($!%*?&,éèàù$#,[])',
                    ]),
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
            ->add('commentaire', TextareaType::class, [
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9@()$!%*?&,éèàù#\[\]]*$/',
                        'message' => 'Caractères spéciaux autorisés ($!%*?&,éèàù$#,[])',
                    ])
                ],
                'attr' => [
                    'placeholder' => 'Écrivez une note...'
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
                    'class' => 'ordre-groupe-items',
                    'style' => 'display: none;'
                ],
            ])
            ->add('choixItems', CollectionType::class, [
                'entry_type' => ChoixItemsType::class,
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
            'data_class' => ItemsGroup::class
        ]);
    }
}
