<?php

namespace App\Form;

use App\Entity\DataRune;
use App\Entity\RunesPage;
use Symfony\Component\Form\FormEvent;
use App\Repository\DataRuneRepository;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Validator\Constraints as Assert;

class RunesPageType extends AbstractType
{
    private $dataRuneRepository;
    private $types = ['Primary', 'Secondary1', 'Secondary2', 'Secondary3'];

    // Injection du repository via le constructeur
    public function __construct(DataRuneRepository $dataRuneRepository)
    {
        $this->dataRuneRepository = $dataRuneRepository;
    }

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
                'required' => true
            ])
            ->add('choixRunesPages', EntityType::class, [
                'class' => DataRune::class,
                'multiple' => true,
                'expanded' => true,
            ]);

        // Sous formulaires pour chaque Arbre de runes
        $arbres = ['Domination', 'Inspiration', 'Precision', 'Resolve', 'Sorcery'];

        foreach ($arbres as $arbre) {
            $builder->add($arbre, FormType::class, [
                'mapped' => false, // car il ne correspond pas directement à une propriété de l'entité
                'required' => false
            ]);

            // Ajoute un EventListener pour filtrer les runes par type pour chaque arbre
            $builder->get($arbre)->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($arbre) {
                $form = $event->getForm();

                // Boutons radio pour chaque type de rune pour cet arbre
                // Filtrer les runes en fonction de l'arbre et du type pour obtenir les bonnes runes
                foreach ($this->types as $type) {
                    $form->add($type, EntityType::class, [
                        'class' => DataRune::class,
                        'choices' => $this->dataRuneRepository->findByArbreAndType($arbre, $type),
                        'multiple' => false,
                        'expanded' => true,
                    ]);
                }
            });
        };
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RunesPage::class,
        ]);
    }
}
