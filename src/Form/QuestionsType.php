<?php

namespace App\Form;

use App\Entity\Questions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('question')
            ->add('imageFile', FileType::class, [
                'required' => false
            ])
            ->add('bonneReponse_1')
            ->add('bonneReponse_2')
            ->add('bonneReponse_3')
            ->add('mauvaiseReponse_1')
            ->add('mauvaiseReponse_2')
            ->add('mauvaiseReponse_3')
            ->add('points')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Questions::class,
        ]);
    }
}
