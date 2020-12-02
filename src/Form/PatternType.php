<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Pattern;
use App\Entity\Yarn;
use App\Repository\CategoryRepository;
use App\Repository\YarnRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PatternType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class, [
                'label' => 'Nom',
                'help' => 'Un nom de moins de 20 charactères sans chiffre.'
            ])
            ->add('Description', TextareaType::class, [
                'label' => 'Description'
            ])
            ->add('difficulty', RangeType::class, [
                'label' => 'Niveau de difficulté',
                'attr' => [
                    'min' => 1,
                    'max' => 5
                ]
            ])
            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'class' => Category::class,
                'help' => 'Sélectionner une catégorie',
                'choice_label' => 'name',
                'query_builder' => function (CategoryRepository $categoryRepository) {
                    return $categoryRepository->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
            ])
            ->add('yarns', EntityType::class, [
                'label' => 'Fil(s)',
                'class' => Yarn::class,
                'help' => 'Sélectionner un ou plusieurs fils',
                'choice_label' => 'name',
                'expanded' => true,
                'multiple' => true,
                'query_builder' => function (YarnRepository $repo) {
                    return $repo->createQueryBuilder('y')
                        ->orderBy('y.name', 'ASC');
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pattern::class,
        ]);
    }
}
