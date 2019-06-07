<?php

namespace App\Form;

use App\Entity\Activity;
use App\Entity\Classes;
use App\Entity\ResultSearch;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResultSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var User $user */
        $user = $options['user'];
        $builder
            ->add('classe', EntityType::class, [
                'class' => Classes::class,
                'choice_label' => 'nom',
                'required' => false,
                'label' => false,
                'query_builder' => function (EntityRepository $er) use ($user){
                    return $er->createQueryBuilder('c')
                        ->OrWhere('c.id in (:arrayId)')
                        ->setParameter('arrayId', $user->getClasses()->getValues());
                },
                'attr' => [
                    'placeholder' => 'Classe choisie'
                ]
            ])
            ->add('matiere', EntityType::class, [
                'class' => Activity::class,
                'choice_label' => 'name',
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'activité choisie'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ResultSearch::class,
            'method' => 'get',
            'csrf_protection' => false,
            'user' => null
        ]);
    }

    public function getBlockPrefix(){
        return '';
    }
}
