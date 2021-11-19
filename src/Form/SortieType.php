<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $dateHeureDebutMin = new DateTime("tomorrow");
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la sortie :'
            ])
            ->add('dateHeureDebut', DateTimeType::class, [
                'label' => 'Date et heure de la sortie :',
                'html5' => true,
                'widget' => 'single_text',
                'data' =>  new \DateTime("tomorrow"),
                'attr' => ['min' => "".$dateHeureDebutMin->format('Y-m-d\TH:i:s').""]
            ])
            ->add('dateLimiteInscription', DateType::class, [
                'label' => 'Date limite d\'inscription :',
                'html5' => true,
                'widget' => 'single_text',
                'disabled' => true,
                'attr' => ['min' => "".$dateHeureDebutMin->format('Y-m-d').""]
            ])
            ->add('nbInscriptionsMax', IntegerType::class, [
                'label' => 'Nombre de places :',
                'data' => 1,
                'attr' => ['step' => 1, 'min' => 1, 'max' => 500]
            ])
            ->add('duree', IntegerType::class, [
                'label' => 'Durée (en minutes) :',
                'data' => 90,
                'attr' => ['step' => 5, 'min' => 5]
            ])
            ->add('infosSortie', TextareaType::class, [
                'label' => 'Description et infos :',
                'attr' => ['rows' => 7]
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
            ->add('rue', EntityType::class, [
                'label' => 'Rue :',
                'class' => lieu::class,
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
                'class' => lieu::class,
                'choice_label' => 'latitude',
                'mapped' => false,
                'disabled' => true
            ])
            ->add('longitude', EntityType::class, [
                'label' => 'Longitude :',
                'class' => lieu::class,
                'choice_label' => 'longitude',
                'mapped' => false,
                'disabled' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
