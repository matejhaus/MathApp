<?php

namespace App\Form;

use App\Entity\Theme;
use App\Entity\User;
use App\Entity\UserAttempts;
use App\Entity\UserStatistics;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserAttemptsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'first_name',
                'placeholder' => 'Choose a user',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->orderBy('t.id', 'ASC');
                },
                'disabled' => in_array('user', $options['disabled_fields'] ?? []),
            ])
            ->add('theme', EntityType::class, [
                'class' => Theme::class,
                'choice_label' => 'name',
                'placeholder' => 'Choose a theme',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->orderBy('t.name', 'ASC');
                },
                'disabled' => in_array('theme', $options['disabled_fields'] ?? []),
            ])
            ->add('correct_answers', IntegerType::class)
            ->add('incorrect_answers', IntegerType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserAttempts::class,
            'disabled_fields' => [],
        ]);
    }
}
