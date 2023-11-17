<?php

namespace App\Form;

use App\Entity\DataItem;
use App\Entity\ChoixItems;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ChoixItemsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dataItem', EntityType::class, [
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
                'allow_extra_fields' => true,
            ]);

        // $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
        //     $form = $event->getForm();
        //     $data = $event->getData();

        //     // Ajout champ ordre pour chaque DataItem sélectionné
        //     if ($data && $data->getDataItem()) {
        //         $form->add('ordre', IntegerType::class, [
        //             'required' => true,
        //             'attr' => [
        //                 'class' => 'ordre-item',
        //             ],
        //         ]);
        //     }
        // });

        // $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
        //     $data = $event->getData();
        //     $form = $event->getForm();

        //     // Gestion champs ordre
        //     if (isset($data['choixItems']) && !empty($data['choixItems'])) {
        //         $form->add('ordre', IntegerType::class, [
        //             'required' => true,
        //             'attr' => [
        //                 'class' => 'ordre-item',
        //             ],
        //         ]);
        //     }
        // });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ChoixItems::class,
            'allow_extra_fields' => true,
        ]);
    }
}
