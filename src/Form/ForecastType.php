<?php

namespace App\Form;

use App\DTO\ForecastDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ForecastType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('city', TextType::class, [
                'label' => 'Miasto',
            ])
            ->add('country', CountryType::class, [
                'label' => 'Kraj',
                'choice_translation_locale' => 'pl',
                'preferred_choices' => [ 'PL' ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Zapisz',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ForecastDTO::class,
        ]);
    }
}
