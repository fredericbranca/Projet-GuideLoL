<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ChangePseudoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('newPseudo', TextType::class, [
                'label' => 'Nouveau pseudo',
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'max' => 20,
                        'minMessage' => 'Votre pseudo doit comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'Votre pseudo ne peut pas comporter plus de {{ limit }} caractères.'
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9]*$/',
                        'message' => 'Votre pseudo ne peut contenir que des lettres et des chiffres.'
                    ]),
                    new Regex([
                        'pattern' => '/^(?!user\d+$).*/i',
                        'message' => 'Votre pseudo ne peut pas être sous la forme "user" suivi de chiffres.'
                    ])
                ],
                'required' => true,
                'attr' => [
                    'placeholder' => 'Pseudo...',
                ],
            ])
            ->add('Valider', SubmitType::class, [
                'label' => 'Changer le pseudo'
            ]);
    }
}
