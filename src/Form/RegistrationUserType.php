<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            // ->add('username')
            ->add('nom')
            ->add('prenom')
            ->add('societe')
            ->add('type',ChoiceType::class,[
                'choices'  => [
                    '' => ' ',
                    'candidat' => 'candidat',
                    'jobber' => 'jobber',
                ],
            ])
            ->add('password',PasswordType::class)
            ->add('confirm_password',PasswordType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
