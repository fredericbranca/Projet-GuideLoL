<?php

namespace App\Form;

use App\Entity\SortInvocateur;
use App\Entity\DataSortInvocateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortInvocateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('commentaire')
            ->add('ordre')
            ->add('choixSortInvocateur', EntityType::class, [
                'class' => DataSortInvocateur::class,
                'multiple' => true,
                'expanded' => true, 
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SortInvocateur::class,
        ]);
    }
}
