<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Category;
use App\Entity\HtmlCode;
use App\Entity\Level;
use App\Entity\Link;
use App\Entity\SchoolStage;
use App\Form\FileUpload\ImageType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class,[
                'label' => 'Título'
            ])
            ->add('source',ChoiceType::class,[
                'label' => 'Destinado a',
                'choices' => [
                    'Estudiante' => 'Estudiante',
                    'Docente' => 'Docente',
                ],
                'multiple' => true,
            ])
            ->add('category', EntityType::class,[
                'class'=> Category::class,
                'query_builder' => function (EntityRepository $repository){
                    return $repository->createQueryBuilder('e');
                },
                'label' => 'Categoría'
            ])
            ->add('stage', EntityType::class,[
                'label' => 'Sección escolar',
                'class'=> SchoolStage::class,
            ])
            ->add('level', EntityType::class,[
                'label' => 'Nivel',
                'class'=> Level::class,
            ])
            ->add('portada',ImageType::class,[
                'label' => 'Portada'
            ])
            ->add('link',LinkType::class,[
                'label' => false,
            ])
            ->add('htmlCode' ,HtmlCodeType::class,[
                'label' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}