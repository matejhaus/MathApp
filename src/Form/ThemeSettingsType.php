<?php

namespace App\Form;

use App\Entity\TestSettings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ThemeSettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('timeLimitInMinutes', IntegerType::class, [
                'label' => 'Časový limit v minutách',
                'required' => false,
            ])
            ->add('numberOfQuestions', IntegerType::class, [
                'label' => 'Počet otázek',
                'required' => false,
            ])
            ->add('randomOrder', CheckboxType::class, [
                'label' => 'Náhodné řazení',
                'required' => false,
                'attr' => ['class' => 'custom-checkbox']
            ])
            ->add('showCorrectAnswersAfter', CheckboxType::class, [
                'label' => 'Zobrazit správné odpovědi po dokončení',
                'required' => false,
                'attr' => ['class' => 'custom-checkbox']
            ])
            ->add('isPracticeMode', CheckboxType::class, [
                'label' => 'Procvičovací mód',
                'required' => false,
                'attr' => ['class' => 'custom-checkbox']
            ])
            ->add('grade1Percentage', NumberType::class, [
                'label' => 'Známka 1 od (%)',
                'required' => false,
            ])
            ->add('grade2Percentage', NumberType::class, [
                'label' => 'Známka 2 od (%)',
                'required' => false,
            ])
            ->add('grade3Percentage', NumberType::class, [
                'label' => 'Známka 3 od (%)',
                'required' => false,
            ])
            ->add('grade4Percentage', NumberType::class, [
                'label' => 'Známka 4 od (%)',
                'required' => false,
            ])
            ->add('grade5Percentage', NumberType::class, [
                'label' => 'Známka 5 od (%)',
                'required' => false,
            ])
            ->add('access_code', PasswordType::class, [
                'required' => false,
                'label' => 'Password',
                'attr' => ['class' => 'password-field'],
            ])
            ->add('show_access_code', CheckboxType::class, [
                'required' => false,
                'label' => 'Zobrazit heslo',
                'mapped' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TestSettings::class,
        ]);
    }
}
