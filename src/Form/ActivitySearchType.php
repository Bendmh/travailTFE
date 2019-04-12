<?php

namespace App\Form;

use App\Entity\Activity;
use App\Entity\ActivitySearch;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActivitySearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('creator_name', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'nom',
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Professeur',
                    'class' => 'selectCustom'
                ]
            ])
            ->add('activity_name', EntityType::class, [
                'class' => Activity::class,
                'choice_label' => 'name',
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Nom de l\'activité',
                    'class' => 'selectCustom'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ActivitySearch::class,
            'method' => 'get',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix(){
        return '';
    }
}
