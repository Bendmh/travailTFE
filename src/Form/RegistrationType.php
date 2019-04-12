<?php

namespace App\Form;

use App\Entity\Classes;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('pseudo')
            ->add('password', PasswordType::class)
            ->add('confirm_password', PasswordType::class)
            ->add('titre', ChoiceType::class,[
                'choices' => [
                    'Professeur' => 'ROLE_PROFESSEUR',
                    'Elève' => 'ROLE_ELEVE'
                ],
                'attr' => [
                    'class' => 'selectChange'
                ]
            ])
            ->add('classes', EntityType::class, [
                'class' => Classes::class,
                'choice_label' => 'nom',
                'label' => 'Classes possibles',
                'expanded' => false,
                'mapped' => true,
                'by_reference' =>false,
                'multiple' => true,
                'attr' => [
                    'class' => 'selectClasses'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
