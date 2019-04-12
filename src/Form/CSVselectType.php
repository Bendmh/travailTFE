<?php

namespace App\Form;

use App\Entity\CSV;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;

class CSVselectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('column1', ChoiceType::class, [
                'choices' => [
                    $builder->getData()->getColumn1() => $builder->getData()->getColumn1(),
                    $builder->getData()->getColumn2() => $builder->getData()->getColumn2(),
                    $builder->getData()->getColumn3() => $builder->getData()->getColumn3(),
                    $builder->getData()->getColumn4() => $builder->getData()->getColumn4(),
                    $builder->getData()->getColumn5() => $builder->getData()->getColumn5(),
                    $builder->getData()->getColumn6() => $builder->getData()->getColumn6(),
                    $builder->getData()->getColumn7() => $builder->getData()->getColumn7(),
                ],
                'label' => "Colonne correspondant au nom de l'activité",
                'required' => false
            ])
            ->add('column2', ChoiceType::class, [
                'choices' => [
                    $builder->getData()->getColumn1() => $builder->getData()->getColumn1(),
                    $builder->getData()->getColumn2() => $builder->getData()->getColumn2(),
                    $builder->getData()->getColumn3() => $builder->getData()->getColumn3(),
                    $builder->getData()->getColumn4() => $builder->getData()->getColumn4(),
                    $builder->getData()->getColumn5() => $builder->getData()->getColumn5(),
                    $builder->getData()->getColumn6() => $builder->getData()->getColumn6(),
                    $builder->getData()->getColumn7() => $builder->getData()->getColumn7(),
                ],
                'label' => "Colonne correspondant à la question",
                'required' => false
            ])
            ->add('column3', ChoiceType::class, [
                'choices' => [
                    $builder->getData()->getColumn1() => $builder->getData()->getColumn1(),
                    $builder->getData()->getColumn2() => $builder->getData()->getColumn2(),
                    $builder->getData()->getColumn3() => $builder->getData()->getColumn3(),
                    $builder->getData()->getColumn4() => $builder->getData()->getColumn4(),
                    $builder->getData()->getColumn5() => $builder->getData()->getColumn5(),
                    $builder->getData()->getColumn6() => $builder->getData()->getColumn6(),
                    $builder->getData()->getColumn7() => $builder->getData()->getColumn7(),
                ],
                'label' => "Colonne correspondant à une bonne réponse",
                'required' => false
            ])
            ->add('column4', ChoiceType::class, [
                'choices' => [
                    $builder->getData()->getColumn1() => $builder->getData()->getColumn1(),
                    $builder->getData()->getColumn2() => $builder->getData()->getColumn2(),
                    $builder->getData()->getColumn3() => $builder->getData()->getColumn3(),
                    $builder->getData()->getColumn4() => $builder->getData()->getColumn4(),
                    $builder->getData()->getColumn5() => $builder->getData()->getColumn5(),
                    $builder->getData()->getColumn6() => $builder->getData()->getColumn6(),
                    $builder->getData()->getColumn7() => $builder->getData()->getColumn7(),
                ],
                'label' => "Colonne correspondant à une bonne réponse",
                'required' => false
            ])
            ->add('column5', ChoiceType::class, [
                'choices' => [
                    $builder->getData()->getColumn1() => $builder->getData()->getColumn1(),
                    $builder->getData()->getColumn2() => $builder->getData()->getColumn2(),
                    $builder->getData()->getColumn3() => $builder->getData()->getColumn3(),
                    $builder->getData()->getColumn4() => $builder->getData()->getColumn4(),
                    $builder->getData()->getColumn5() => $builder->getData()->getColumn5(),
                    $builder->getData()->getColumn6() => $builder->getData()->getColumn6(),
                    $builder->getData()->getColumn7() => $builder->getData()->getColumn7(),
                ],
                'label' => "Colonne correspondant à une mauvaise réponse",
                'required' => false
            ])
            ->add('column6', ChoiceType::class, [
                'choices' => [
                    $builder->getData()->getColumn1() => $builder->getData()->getColumn1(),
                    $builder->getData()->getColumn2() => $builder->getData()->getColumn2(),
                    $builder->getData()->getColumn3() => $builder->getData()->getColumn3(),
                    $builder->getData()->getColumn4() => $builder->getData()->getColumn4(),
                    $builder->getData()->getColumn5() => $builder->getData()->getColumn5(),
                    $builder->getData()->getColumn6() => $builder->getData()->getColumn6(),
                    $builder->getData()->getColumn7() => $builder->getData()->getColumn7(),
                ],
                'label' => "Colonne correspondant à une mauvaise réponse",
                'required' => false
            ])
            ->add('column6', ChoiceType::class, [
                'choices' => [
                    $builder->getData()->getColumn1() => $builder->getData()->getColumn1(),
                    $builder->getData()->getColumn2() => $builder->getData()->getColumn2(),
                    $builder->getData()->getColumn3() => $builder->getData()->getColumn3(),
                    $builder->getData()->getColumn4() => $builder->getData()->getColumn4(),
                    $builder->getData()->getColumn5() => $builder->getData()->getColumn5(),
                    $builder->getData()->getColumn6() => $builder->getData()->getColumn6(),
                    $builder->getData()->getColumn7() => $builder->getData()->getColumn7(),
                ],
                'label' => "Colonne correspondant à une mauvaise réponse",
                'required' => false
            ])
            ->add('column7', ChoiceType::class, [
                'choices' => [
                    $builder->getData()->getColumn1() => $builder->getData()->getColumn1(),
                    $builder->getData()->getColumn2() => $builder->getData()->getColumn2(),
                    $builder->getData()->getColumn3() => $builder->getData()->getColumn3(),
                    $builder->getData()->getColumn4() => $builder->getData()->getColumn4(),
                    $builder->getData()->getColumn5() => $builder->getData()->getColumn5(),
                    $builder->getData()->getColumn6() => $builder->getData()->getColumn6(),
                    $builder->getData()->getColumn7() => $builder->getData()->getColumn7(),
                ],
                'label' => "Colonne correspondant au nombre de points"
            ])
            ->add('type', EntityType::class, [
                'class' => \App\Entity\ActivityType::class,
                'choice_label' => 'name'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CSV::class,
        ]);
    }
}
