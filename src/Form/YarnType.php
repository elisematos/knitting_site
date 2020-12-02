<?php

namespace App\Form;

use App\Entity\Brand;
use App\Entity\Yarn;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class YarnType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,[
                'label' => 'Nom'
            ])
            ->add('weight', ChoiceType::class,[
                'label' => 'Catégorie',
                'choices' => Yarn::YARN_WEIGHT
            ])
            ->add('brand', EntityType::class,[
                'label' => 'Marque',
                'class' => Brand::class,
                'choice_label' => 'name'
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Yarn::class,
        ]);
    }
}
