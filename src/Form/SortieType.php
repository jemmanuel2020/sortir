<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('$nomCampus', TextType::class, [
                'label' => 'Nom de la sortie :'
            ])
            ->add('dateHeureDebut', DateType::class, [
                'label' => 'Date et heure de la sortie :',
                'html5' => true,
                'widget' => 'single_text'
            ])
            ->add('dateLimiteInscription', DateType::class, [
                'label' => 'Date limite d\'inscription :',
                'html5' => true,
                'widget' => 'single_text'
            ])
            ->add('nbInscriptionsMax', IntegerType::class, [
                'label' => 'Nombre de places :'
            ])
            ->add('duree', TextType::class, [
                'label' => 'Durée :'
            ])
            ->add('infosSortie', null, [
                'label' => 'Description et infos :'
            ])
            ->add('campus', TextType::class, [
                'label' => 'Campus :'
            ])
            //Ville
            ->add('lieu', TextType::class, [
                'label' => 'Lieu :'
            ])
            // Rue
            //Code postal
            //Latitude
            //Longitude
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
