<?php

namespace Rayenbou\DashboardBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options['title']) {
            $builder
                ->add('title', TextType::class, [
                    'label' => 'Title',
                    'required' => true,
                    'constraints' => [
                        new NotBlank([
                            'message' => 'This field cannot be empty.',
                        ]),
                        new Length([
                            'min' => 5,
                            'maxMessage' => 'This field cannot exceed {{ limit }} characters.',
                        ]),
                    ],
                    'attr' => [
                        'class' => 'form-control',
                    ],
                ]);
        }
        $builder->add('description', TextareaType::class, [
            'label' => 'Message',
            'required' => true,
            'constraints' => [
                new NotBlank([
                    'message' => 'This field cannot be empty.',
                ]),
                new Length([
                    'min' => 5,
                    'maxMessage' => 'This field cannot exceed {{ limit }} characters',
                ]),
            ],
            'attr' => [
                'class' => 'form-control',
            ],
        ]);
        if ($options['author']) {
            $builder
                ->add(
                    'author',
                    TextType::class,
                    [
                        'label' => 'Author',
                        'required' => true,
                        'constraints' => [
                            new NotBlank([
                                'message' => 'This field cannot be empty.',
                            ]),
                            new Length([
                                'min' => 5,
                                'maxMessage' => 'This field cannot exceed {{ limit }} characters.',
                            ]),
                        ],
                        'attr' => [
                            'class' => 'form-control',
                        ],
                    ]
                );
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
