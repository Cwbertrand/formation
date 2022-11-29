<?php

namespace App\Form;

use App\Entity\Module;
use App\Entity\Programme;
use App\Entity\InstituleSession;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ProgrammeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nbjours', NumberType::class,[
                'label' => false,
            ])
            ->add('instituleSession', EntityType::class,[
                'class' => InstituleSession::class,
                'label' => false,
            ])
            ->add('module', EntityType::class,[
                'class' => Module::class,
                'label' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Programme::class,
        ]);
    }
}
