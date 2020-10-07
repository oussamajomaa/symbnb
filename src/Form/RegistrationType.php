<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->attr("Prénom","Entrer votre prénom"))
            ->add('lastName', TextType::class, $this->attr("Nom","Entrer votre nom"))
            ->add('email', EmailType::class, $this->attr("Adresse mail","Entrer votre adresse mail"))
            ->add('picture', TextType::class, $this->attr("Avatar","Entrer le url de l'image"))
            ->add('hash', PasswordType::class, $this->attr("Mot de passe","Entrer le mot de apsse"))
            ->add('confirmPassword', PasswordType::class, $this->attr("Confirmer mot de passe", "Retapez le mot de passe à nouveau"))
            ->add('introduction', TextType::class, $this->attr("Introduction","Une petite introduction"))
            ->add('description', TextareaType::class, $this->attr("Description","Une longue description"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
