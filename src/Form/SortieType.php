<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

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
                'widget' => 'single_text',
                'attr' => ['min' => "".$dateHeureDebutMin->format('Y-m-d\TH:i:s').""]
            ])
            ->add('dateLimiteInscription', DateType::class, [
                'label' => 'Date limite d\'inscription :',
                'html5' => true,
                'widget' => 'single_text',
                'attr' => ['min' => "".$dateLimiteInscriptionMax->format('Y-m-d').""]
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
            $builder
                ->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'))
                ->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'))
            ;
    }

    protected function addElements(FormInterface $form, Ville $ville = null)
    {
        // Ajout du champ ville
        $form->add('ville', EntityType::class, array(
            'required' => true,
            'data' => $ville,
            'class' => Ville::class,
            'choice_label' => 'nom',
            'mapped' => false
        ));

        // Lieu vide, sauf s'il y a une ville sélectionnée (Edit View)
        $lieux = [];

        // If there is a ville stored in the Sortie entity, load the lieu of it
        if ($ville)
        {
            // Fetch Lieu of the Ville if there's a selected ville
            $lieuRepository = $this->entityManager->getRepository(Lieu::class);
            $lieux = $lieuRepository->findLieuByCity($ville);
        }

        // Ajout du champ lieu avec les bonnes données
        $form->add('lieu', EntityType::class, array(
            'required' => true,
            'class' => Lieu::class,
            'choices' => $lieux,
            'choice_label' => 'nom'
        ));
    }

    function onPreSubmit(FormEvent $event) {
        $form = $event->getForm();
        $data = $event->getData();

        // Search for selected Ville and convert it into an Entity
        $ville = $this->entityManager->getRepository(Ville::class)->find($data['ville']);

        $this->addElements($form, $ville);
    }

    function onPreSetData(FormEvent $event) {
        $sortie = $event->getData();
        $form = $event->getForm();

        // When you create a new sortie, the Ville is always empty
        $ville = $sortie->getLieu() ? $sortie->getLieu()->getVille() : null;

        $this->addElements($form, $ville);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
