<?php

namespace App\Form;

use App\Entity\Theme;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ThemeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('category', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new Assert\Range([
                        'min' => 1,
                        'max' => 2,
                    ]),
                ],
            ])
            ->add('content', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 15,
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Theme::class,
        ]);
    }
}
