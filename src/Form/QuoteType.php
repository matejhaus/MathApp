<?php

namespace App\Form;

use App\Entity\Quotes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

class QuoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('position', IntegerType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Pozice musí být vyplněna.',
                    ]),
                    new PositiveOrZero([
                        'message' => 'Pozice musí být nezáporné číslo.',
                    ]),
                ],
            ])
            ->add('quote', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Citát musí být vyplněn.',
                    ]),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'Citát nesmí být delší než 255 znaků.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quotes::class,
        ]);
    }
}
