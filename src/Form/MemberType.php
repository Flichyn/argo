<?php

namespace App\Form;

use App\Entity\Member;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class MemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->
        add('name', TextType::class, [
            'label' => 'Nom de l\'Argonaute',
            'attr' => [
                'placeholder' => 'Charalampos',
            ],
            'required' => true,
            'constraints' => [
                new NotNull([
                    'message' => 'Vous devez nommer le jeu que vous voulez ajouter.',
                ])
            ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Member::class,
        ]);
    }
}
