<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ExerciseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('min_value', IntegerType::class, [
                'label' => 'Minimální hodnota:',
                'attr' => [
                    'min' => 1,
                    'max' => 100,
                    'required' => true,
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Minimální hodnota nesmí být prázdná.']),
                    new Assert\Range([
                        'min' => 1,
                        'max' => 100,
                        'notInRangeMessage' => 'Minimální hodnota musí být mezi {{ min }} a {{ max }}.',
                    ]),
                ],
            ])
            ->add('max_value', IntegerType::class, [
                'label' => 'Maximální hodnota:',
                'attr' => [
                    'min' => 1,
                    'max' => 100,
                    'required' => true,
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Maximální hodnota nesmí být prázdná.']),
                    new Assert\Range([
                        'min' => 1,
                        'max' => 100,
                        'notInRangeMessage' => 'Maximální hodnota musí být mezi {{ min }} a {{ max }}.',
                    ]),
                    new Assert\Callback([$this, 'validateMaxValue']),
                ],
            ])
            ->add('number_of_examples', IntegerType::class, [
                'label' => 'Počet příkladů:',
                'attr' => [
                    'min' => 1,
                    'max' => 20,
                    'required' => true,
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Počet příkladů nesmí být prázdný.']),
                    new Assert\Range([
                        'min' => 1,
                        'max' => 20,
                        'notInRangeMessage' => 'Počet příkladů musí být mezi {{ min }} a {{ max }}.',
                    ]),
                ],
            ])
            ->add('difficulty', ChoiceType::class, [
                'label' => 'Náročnost:',
                'choices' => $this->getDifficultyChoices($options['theme_id']),
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Musíte zvolit náročnost.']),
                ],
            ])
            ->add('theme', TextType::class, [
                'label' => 'Téma',
                'data' => $options['theme_name'],
                'attr' => [
                    'disabled' => true,
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Generovat',
                'attr' => ['class' => 'btn-primary'],
            ]);
    }

    private function getDifficultyChoices(int $themeId): array
    {
        if ($themeId === 10) {
            return [
                'Čtverec' => 'square',
                'Obdélník' => 'rectangle',
                'Trojúhelník' => 'triangle',
                'Kruh' => 'circle',
            ];
        }

        return [
            'Jednoduchá' => 'easy',
            'Střední' => 'medium',
            'Těžká' => 'hard',
        ];
    }

    public function validateMaxValue($value, ExecutionContextInterface $context)
    {
        $form = $context->getRoot();
        $minValue = $form->get('min_value')->getData();

        if ($minValue !== null && $value <= $minValue) {
            $context->buildViolation('Maximální hodnota musí být větší než minimální hodnota.')
                ->atPath('max_value')
                ->addViolation();
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'theme_id' => null,
            'theme_name' => null,
        ]);
    }
}
