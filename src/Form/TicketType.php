<?php

namespace Rayenbou\DashboardBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        if ($options['title']) {
            $builder
                ->add('title');
        }
        if ($options['description']) {
            $builder
                ->add('description');
        }
        if ($options['author']) {
            $builder
                ->add('author');
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'title' => true,
            'description' => true,
            'author' => true,
            // Configure your form options here
        ]);
    }
}
