<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\IsTrue;

class CarDeleteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('confirm', CheckboxType::class, array(
                'label'    => 'Do you really want to delete this car from stock?',
                'required' => true,
                'constraints'=> new IsTrue(array('message'=>'Please confirm'))
            ))
            ->add('save', SubmitType::class);
    }

}