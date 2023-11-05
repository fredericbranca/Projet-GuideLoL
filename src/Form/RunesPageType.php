<?php

namespace App\Form;

use App\Entity\DataRune;
use App\Entity\RunesPage;
use App\Entity\DataStatistiqueBonus;
use Symfony\Component\Form\FormEvent;
use App\Repository\DataRuneRepository;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\DataStatistiqueBonusRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class RunesPageType extends AbstractType
{
    private $dataRuneRepository;
    private $dataStatistiqueBonusRepository;
    private $types = ['Primary', 'Secondary1', 'Secondary2', 'Secondary3'];

    // Injection du repository via le constructeur
    public function __construct(DataRuneRepository $dataRuneRepository, DataStatistiqueBonusRepository $dataStatistiqueBonusRepository)
    {
        $this->dataRuneRepository = $dataRuneRepository;
        $this->dataStatistiqueBonusRepository = $dataStatistiqueBonusRepository;
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


                // Ajoute un input hidden pour stocker le type d'arbre (primaire / secondaire), qui sera rempli en JS
                $form->add('typeArbre', HiddenType::class, [
                    'mapped' => false,
                    'required' => false,
                    'attr' => [
                        'class' => 'type-arbre-hidden',
                        'data-arbre-type' => $arbre
                    ],
                ]);

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

        // Ajoute les champs bonus
        $bonusLines = [1, 2, 3]; // Les lignes de bonus disponibles

        foreach ($bonusLines as $line) {
            $builder->add('bonusLine' . $line, EntityType::class, [
                'class' => DataStatistiqueBonus::class,
                'choices' => $this->dataStatistiqueBonusRepository->findBy(['bonus_line' => $line]),
                'choice_label' => 'bonus_value',
                'expanded' => true,
                'multiple' => false,
                'mapped' => false,
                'required' => true
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RunesPage::class,
        ]);
    }
}
