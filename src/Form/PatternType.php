<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Image;
use App\Entity\Pattern;
use App\Entity\Yarn;
use App\Repository\CategoryRepository;
use App\Repository\YarnRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PatternType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class, [
                'label' => false,
                'help' => 'Moins de 20 caractères sans chiffre',
                'attr' => [
                    'placeholder' => 'Nom'
                    ]
            ])
            ->add('description', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Description'
                ]
            ])
            ->add('skillLevel', ChoiceType::class, [
                'label' => false,
                'choices' => Pattern::SKILL_LEVEL,
                'placeholder' => 'Niveau de difficulté'
            ])
            ->add('category', EntityType::class, [
                'label' => false,
                'class' => Category::class,
                'placeholder' => 'Catégorie',
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
            ->add('pdf', FileType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Patron au format pdf'],
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '1M',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Veuillez choisir un fichier pdf',
                    ])
                ],
            ])
            ->add('images', FileType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Images'],
                'multiple' => true,
                'mapped' => false,
                'required' => true,
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
