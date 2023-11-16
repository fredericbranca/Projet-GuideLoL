<?php

namespace App\Form;

use App\Entity\DataCompetence;
use App\Entity\CompetencesGroup;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CompetenceType extends AbstractType
{
    private $entityManager;

    // Injection du repository via le constructeur
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

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
                    'class' => 'ordre',
                    'style' => 'display: none;'
                ],
            ])
            ->add('competence', FormType::class, [
                'mapped' => false,
                'required' => false,
                'allow_extra_fields' => true,
            ]);

        $competencesForm = $builder->get('competence');
        $championId = $options['champion_id'];

        // Récupération des données de compétence
        $repository = $this->entityManager->getRepository(DataCompetence::class);
        $dataCompetences = $repository->findBy(['nomChampion' => $championId]);

        // Trier les compétences dans l'ordre A, Z, E, R
        usort($dataCompetences, function ($a, $b) {
            $order = ['A' => 0, 'Z' => 1, 'E' => 2, 'R' => 3];
            return $order[$a->getType()] - $order[$b->getType()];
        });

        foreach ($dataCompetences as $dataCompetence) {
            $competenceType = $dataCompetence->getType();
            $competenceId = $dataCompetence->getId();
            $maxChoices = $competenceType === 'R' ? 3 : 5;  // Limite de 3 choix pour R, 5 choix pour A, Z, et E

            $competencesForm->add($competenceId, ChoiceType::class, [
                'choices' => array_combine(range(1, 18), range(1, 18)),
                'multiple' => true,
                'expanded' => true,
                'choice_attr' => function ($choice, $key, $value) use ($competenceType) {
                    return ['data-competence' => $competenceType, 'data-choice' => $choice];
                },
                'attr' => ['data-max-choices' => $maxChoices],
                'label' => $competenceType
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CompetencesGroup::class,
            'allow_extra_fields' => true,
        ]);

        // Option champion_id
        $resolver->setDefined('champion_id');
    }
}
