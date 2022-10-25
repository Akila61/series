<?php

namespace App\Form;

use App\Entity\Season;
use App\Entity\Serie;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeasonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('serie', EntityType::class, [
                'class' => Serie::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')->orderBy('s.name', 'ASC');
                },
                'choice_label' => 'name'
            ])
            ->add('number')
            ->add('firstAirDate')
            ->add('overview')
            ->add('poster')
            ->add('tmdbId')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Season::class,
        ]);
    }
}
