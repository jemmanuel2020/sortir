<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Repository\LieuRepository;
use App\Repository\VilleRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    /*private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }*/

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $dateHeureDebutMin = new DateTime("tomorrow");
        $dateLimiteInscriptionMax = new DateTime("now");

        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la sortie :'
            ])
            ->add('dateHeureDebut', DateTimeType::class, [
                'label' => 'Date et heure de la sortie :',
                'html5' => true,
                'widget' => 'single_text'
                //'attr' => ['min' => "".$dateHeureDebutMin->format('Y-m-d\TH:i:s').""]
            ])
            ->add('dateLimiteInscription', DateType::class, [
                'label' => 'Date limite d\'inscription :',
                'html5' => true,
                'widget' => 'single_text'
                //'attr' => ['min' => "".$dateLimiteInscriptionMax->format('Y-m-d').""]
            ])
            ->add('nbInscriptionsMax', IntegerType::class, [
                'label' => 'Nombre de places :',
                'attr' => ['step' => 1, 'min' => 1, 'max' => 500]
            ])
            ->add('duree', IntegerType::class, [
                'label' => 'Durée (en minutes) :',
                'attr' => ['step' => 5, 'min' => 5]
            ])
            ->add('infosSortie', TextareaType::class, [
                'label' => 'Description et infos :',
                'attr' => ['rows' => 7],
                'required' => false
            ])
            ->add('campus', EntityType::class, [
                'label' => 'Campus :',
                'class' => Campus::class,
                'choice_label' => 'nom',
                'disabled' => true
            ])
            ->add('ville', EntityType::class, [
                'label' => 'Ville :',
                'class' => Ville::class,
                'choice_label' => 'nom',
                'mapped' => false,
                'data' => null
            ])
            ->add('lieu', EntityType::class, [
                'label' => 'Lieu :',
                'class' => Lieu::class,
                'choice_label' => 'nom',
            ])
            /*->add('lieu', ChoiceType::class, [
                'label' => 'Lieu :'
            ]);*/
            /*->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $sortie = $event->getData();

                $form = $event->getForm();

                $formOptions = [
                    'class' => Lieu::class,
                    'choice_label' => 'nom',
                    'query_builder' => function (LieuRepository $lieuRepository) {
                        return $lieuRepository->findLieuByCity('Paris');
                    },
                ];

                // create the field, this is similar the $builder->add()
                // field name, field type, field options
                $form->add('lieu', EntityType::class, $formOptions);

                // checks if the Sortie object is "new"
                // If no data is passed to the form, the data is "null".
                // This should be considered a new "Sortie"
                if (!$sortie || null === $sortie->getIdSortie()) {
                    $form->add('lieu', ChoiceType::class, [
                        'label' => 'Lieu :'
                    ]);
                }
            })*/
            ->add('rue', EntityType::class, [
                'label' => 'Rue :',
                'class' => Lieu::class,
                'choice_label' => 'rue',
                'mapped' => false,
                'disabled' => true
            ])
            ->add('code_postal', EntityType::class, [
                'label' => 'Code Postal :',
                'class' => Ville::class,
                'choice_label' => 'code_postal',
                'mapped' => false,
                'disabled' => true
            ])
            ->add('latitude', EntityType::class, [
                'label' => 'Latitude :',
                'class' => Lieu::class,
                'choice_label' => 'latitude',
                'mapped' => false,
                'disabled' => true
            ])
            ->add('longitude', EntityType::class, [
                'label' => 'Longitude :',
                'class' => Lieu::class,
                'choice_label' => 'longitude',
                'mapped' => false,
                'disabled' => true
            ])
        ;
            /*$builder
                ->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'))
                ->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'))
            ;*/
    }

    /*protected function addElements(FormInterface $form, Ville $ville = null)
    {
        // 4. Add the lieu element
        $form->add('lieu', EntityType::class, array(
            'required' => true,
            'data' => $ville,
            'class' => Lieu::class
        ));

        // Lieu empty, unless there is a selected Ville (Edit View)
        $lieu = array();

        // If there is a ville stored in the Sortie entity, load the lieu of it
        if ($ville)
        {
            // Fetch Lieu of the Ville if there's a selected ville
            $lieuRepository = $this->entityManager->getRepository('App:Lieu');
            $lieu = $lieuRepository->findLieuByCity($ville);
        }

        // Add the Lieu field with the properly data
        $form->add('lieu', EntityType::class, array(
            'required' => true,
            'class' => Lieu::class,
            'choices' => $lieu
        ));
    }

    function onPreSubmit(FormEvent $event) {
        $form = $event->getForm();
        $data = $event->getData();

        // Search for selected Ville and convert it into an Entity
        $ville = $this->entityManager->getRepository('App:Ville')->find($data['ville']);

        $this->addElements($form, $ville);
    }

    function onPreSetData(FormEvent $event) {
        $sortie = $event->getData();
        $form = $event->getForm();

        // When you create a new person, the City is always empty
        $ville = $sortie->getLieu() ? $sortie->getLieu() : null;

        $this->addElements($form, $ville);
    }*/

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
