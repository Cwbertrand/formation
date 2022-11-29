<?php

namespace App\Form;

use App\Entity\InstituleSession;
use App\Entity\Programme;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class InstituleSessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('themesession', TextType::class, [
                'label' => false,
            ])
            ->add('placetotal', NumberType::class, [
                'label' => false,
            ])
            ->add('datecommerce', DateType::class, [
                'label' => false,
            ])
            ->add('datefin', DateType::class, [
                'label' => false,
            ])
            ->add('description', TextareaType::class, [
                'label' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InstituleSession::class,
        ]);
    }
}
