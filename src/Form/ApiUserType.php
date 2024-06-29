<?php

namespace Rayenbou\DashboardBundle\Form;


use Symfony\Component\Form\AbstractType;
use Rayenbou\DashboardBundle\Entity\ApiUser;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApiUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')

            ->add('password');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ApiUser::class,
        ]);
    }
}
