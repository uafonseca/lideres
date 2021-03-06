<?php

namespace App\Form;

use App\Entity\Choice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChoicesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',null,[
                'label' => 'Opción'
            ])
            ->add('value',null,[
                'label' => 'Valor',
                'attr' => [
                    'class' => 'choice-value'
                ]
            ])
            ->add('isCorrect',null,[
                'label' => '¿Es correcta?',
                'attr' => [
                    'class' => 'checker'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Choice::class,
        ]);
    }
}
