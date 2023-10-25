<?php

namespace App\Form;

use App\Entity\DataRune;
use App\Entity\RunesPage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RunesPageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('commentaire')
            ->add('ordre')
            ->add('choixRunes', EntityType::class, [
                'class' => DataRune::class,
                'multiple' => true,
                'expanded' => true, 
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RunesPage::class,
        ]);
    }
}