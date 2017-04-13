<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Car;

class CarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('price')
            ->add('isReserved')
            ->add('isSold')
            ->add('make')
            ->add('model')
            ->add('variant')
            ->add('firstRegistration')
            ->add('mileage')
            ->add('mot')
            ->add('doors')
            ->add('seats')
            ->add('colour')
            ->add('body')
            ->add('fuelType')
            ->add('transmission')
            ->add('engineSize')
            ->add('bhp')
            ->add('mpgUrban')
            ->add('mpgExtraUrban')
            ->add('mpgCombined')
            ->add('length')
            ->add('width')
            ->add('countryOfOrigin')
            ->add('carPhotos', FileType::class, array(
                'multiple' => true,
                'data_class' => null,
                'mapped' => false,
            ))
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Car::class,
        ));
    }

}