<?php

namespace App\Form;

use App\Entity\Campus;
use App\Modele\Modele;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiltreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomCampus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'nom',
                'label' => 'Campus',
                'required' => false
            ])
            ->add('nomSortie', SearchType::class, [
                'label' => 'Le nom de la sortie contient:',
                'required' => false
            ])
            ->add('dateSortie1', DateTimeType::class, [
                'label' => 'Entre',
                'html5' => true,
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('dateSortie2', DateTimeType::class, [
                'label' => 'et',
                'html5' => true,
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('organisateur', CheckboxType::class, [
                'label' => 'Sorties dont je suis l\'organisateur/trice',
                'required' => false
            ])
            ->add('inscrit', CheckboxType::class, [
                'label' => 'Sorties auxquelles je suis inscrit/e',
                'required' => false
            ])
            ->add('pasInscrit', CheckboxType::class, [
                'label' => 'Sorties auxquelles je ne suis pas inscrit/e',
                'required' => false
            ])
            //Ville
            ->add('sortiepassees', CheckboxType::class, [
                'label' => 'Sorties passées',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Modele::class,
        ]);
    }
}
