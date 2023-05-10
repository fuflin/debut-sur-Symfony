<?php

namespace App\Form;

use App\Entity\Employe;
use App\Entity\Entreprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EmployeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class) // on précise le type pour chaque champ du form ici du texte
            ->add('prenom', TextType::class)
            ->add('dateNaissance', DateType::class, [ // ici on aura un type date sans les heures
                'widget' => 'single_text'
            ])
            ->add('dateEmbauche', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('ville', TextType::class)
            ->add('entreprise', EntityType::class, [ // ici un type entité avec des précisions
                'class' => Entreprise::class, // on précise à quelle classe se refère le champ
                'choice_label' => 'raisonSociale', // là on choisi ce que l'on veux afficher de la classe en question
            ])
            ->add('submit', SubmitType::class, [ // on a ici un type submit pour le bouton du form
                'attr' => ['class' => 'btn btn-secondary']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employe::class,
        ]);
    }
}
