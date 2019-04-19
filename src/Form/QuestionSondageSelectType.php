<?php

namespace App\Form;

use App\Entity\QuestionSondage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionSondageSelectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if(!is_null($builder->getData()->getChoix3()) && is_null($builder->getData()->getChoix4())){
            $builder
                ->add('repTest', ChoiceType::class, [
                    'choices' => [
                        $builder->getData()->getChoix1() => $builder->getData()->getChoix1(),
                        $builder->getData()->getChoix2() => $builder->getData()->getChoix2(),
                        $builder->getData()->getChoix3() => $builder->getData()->getChoix3(),
                    ],
                    'label' => 'Choisissez une réponse',
                    'required' => true,
                    'expanded' => true,
                    'multiple' => false,
                    'empty_data' => null
                ])
            ;
        }elseif (!is_null($builder->getData()->getChoix4()) && is_null($builder->getData()->getChoix5())){
            $builder
                ->add('repTest', ChoiceType::class, [
                    'choices' => [
                        $builder->getData()->getChoix1() => $builder->getData()->getChoix1(),
                        $builder->getData()->getChoix2() => $builder->getData()->getChoix2(),
                        $builder->getData()->getChoix3() => $builder->getData()->getChoix3(),
                        $builder->getData()->getChoix4() => $builder->getData()->getChoix4(),
                    ],
                    'label' => 'Choisissez une réponse',
                    'required' => true,
                    'expanded' => true,
                    'multiple' => false,
                    'empty_data' => null
                ])
            ;
        }elseif (!is_null($builder->getData()->getChoix5())){
            $builder
                ->add('repTest', ChoiceType::class, [
                    'choices' => [
                        $builder->getData()->getChoix1() => $builder->getData()->getChoix1(),
                        $builder->getData()->getChoix2() => $builder->getData()->getChoix2(),
                        $builder->getData()->getChoix3() => $builder->getData()->getChoix3(),
                        $builder->getData()->getChoix4() => $builder->getData()->getChoix4(),
                        $builder->getData()->getChoix5() => $builder->getData()->getChoix5(),
                    ],
                    'label' => 'Choisissez une réponse',
                    'required' => true,
                    'expanded' => true,
                    'multiple' => false,
                    'empty_data' => null
                ])
            ;
        }else {
            $builder
                ->add('repTest', ChoiceType::class, [
                    'choices' => [
                        $builder->getData()->getChoix1() => $builder->getData()->getChoix1(),
                        $builder->getData()->getChoix2() => $builder->getData()->getChoix2(),
                    ],
                    'label' => 'Choisissez une réponse',
                    'required' => true,
                    'expanded' => true,
                    'multiple' => false,
                    'empty_data' => null
                ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => QuestionSondage::class,
        ]);
    }
}
