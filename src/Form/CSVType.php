<?php

namespace App\Form;

use App\Entity\CSV;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CSVType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('fileCSV', FileType::class, [
                'required' => true
            ])
            ->add('type', EntityType::class, [
                'class' => \App\Entity\ActivityType::class,
                'choice_label' => 'name',
                'required' => true
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
