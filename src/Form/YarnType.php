<?php

namespace App\Form;

use App\Entity\Brand;
use App\Entity\Yarn;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class YarnType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('weight')
            ->add('brand', EntityType::class,[
                'label' => 'name',
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
