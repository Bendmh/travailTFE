<?php

namespace App\Form;

use App\Entity\Classes;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AssignClassesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('classes', EntityType::class, [
                    'class' => Classes::class,
                    'choice_label' => 'nom',
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
