<?php

namespace App\Form;

use App\Form\ApplicationType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordUpdateType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', PasswordType::class, $this->attr("L'ancien mot de passe","Entrer l'ancien mot de passe !"))
            ->add('newPassword', PasswordType::class, $this->attr("Le nouveau mot de passe", "Entrer le nouveau mot de passe !"))
            ->add('confirmPassword', PasswordType::class, $this->attr("Confirmer le mot de passe", "Confirmer le nouveau mot de passe !"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
