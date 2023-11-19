<?php

namespace App\Form;

use App\Entity\DataItem;
use App\Entity\ItemsGroup;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Doctrine\Common\Collections\ArrayCollection;
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
                        'pattern' => '/^[a-zA-Z0-9@()$!%*?&,éèàù#\[\] çÇ]*$/',
                        'message' => 'Caractères spéciaux autorisés ($!%*?&,éèàùçÇ$#,[])',
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
                        'pattern' => '/^[a-zA-Z0-9@()$!%*?&,éèàù#\[\] çÇ]*$/',
                        'message' => 'Caractères spéciaux autorisés ($!%*?&,éèàùçÇ$#,[])',
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
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('d')
                        ->orderBy('d.prix', 'ASC');
                },
                'choice_attr' => function ($choice, $key, $value) {
                    // Applique la classe 'item-checkbox' à chaque checkbox
                    return ['class' => 'item-checkbox'];
                },
            ]);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {

            $form = $event->getForm();
            $data = $event->getData();
        
            if (isset($data['ordreItems']) && is_array($data['ordreItems'])) {
                $form->add('ordreItems', CollectionType::class, [
                    'entry_type' => IntegerType::class,
                    'entry_options' => ['attr' => ['class' => 'ordre-item']],
                    'data' => $data['ordreItems'],
                ]);
            }

        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ItemsGroup::class,
            'allow_extra_fields' => true,
        ]);
    }
}
