<?php

namespace App\Form;

use App\Entity\Activity;
use App\Form\FileUpload\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ActivityFormType
 * @package App\Form
 */
class ActivityFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $type = $options['type'];

        $builder
            ->add('name', null, [
                'label' => 'Nombre de la actividad'
            ])
            ->add('page', null, [
                'label' => 'Página para la actividad',
                'help' => 'El conteo de paginas comienza desde 0',
            ]);

        switch ($type) {
            case Activity::TYPE_GENIALLY:
                $builder
                    ->add('url', null, [
                        'label' => 'Url de la actividad',
                        'required' => false,
                    ]);
                break;
            case Activity::TYPE_AUDIO:
                $builder
                    ->add('file', ImageType::class, [
                        'label' => 'Archivo',
                        'attr' => ['class' => 'audio-file'],
                        'help' => 'Formatos permitidos .mp3, .wav',
                    ]);
                break;
            case Activity::TYPE_YOUTUBE:
                $builder
                    ->add('url', null, [
                        'label' => 'Link del video de Youtube',
                        'attr' => ['class' => 'youtube'],
                        'required' => false,
                    ]);
                break;
            case Activity::TYPE_FILE:
                $builder
                    ->add('file', ImageType::class, [
                        'label' => 'Archivo',
                        'attr' => ['class' => 'file'],
                    ]);
                break;
            case Activity::TYPE_PRESENTATION:
                    $builder
                    ->add('code', null, [
                        'label' => 'Código interactivo',
                        'attr' => ['class' => 'code'],
                        'required' => false,
                        'help' => 'Utilice las siguientes dimensiones para generar su código (485 x 280)',
                    ]);
                    break;
            default:
                $builder
                    ->add('file', ImageType::class, [
                        'label' => 'Archivo',
                        'attr' => ['class' => 'video-file'],
                        'help' => 'Formatos permitidos .mkv, .avi, .wmv, .mp4, .mpg',
                    ]);
                break;
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Activity::class,
        ]);

        $resolver->setRequired('type');
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'activity';
    }
}
