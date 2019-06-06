<?php

namespace App\Form;

use App\Entity\Activity;
use App\Entity\ActivitySearch;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use function foo\func;
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
                'query_builder' => function (EntityRepository $er){
                    return $er->createQueryBuilder('u')
                        ->OrWhere('u.titre <> :titre')
                        ->setParameter('titre', 'ROLE_ELEVE')
                        ->orderBy('u.nom', 'ASC');
                },
                'choice_label' => function($user, $key, $index) {
                    /** @var User $user */
                    return $user->getNom() . ' ' . substr($user->getPrenom(), 0, 1);
                },
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Professeur',
                    'class' => 'selectCustom'
                ]
            ])
            ->add('activity_name', EntityType::class, [
                'class' => Activity::class,
                'query_builder' => function (EntityRepository $er){
                    return $er->createQueryBuilder('u')
                        ->OrWhere('u.visible = :boolean')
                        ->setParameter('boolean', '1')
                        ->orderBy('u.name', 'ASC');
                },
                'choice_label' => 'name',
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Nom de l\'activité',
                    'class' => 'selectCustom'
                ]
            ])
            ->add('activity_type', EntityType::class,[
                'class' => \App\Entity\ActivityType::class,
                'choice_label' => 'name',
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Type de l\'activité',
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
