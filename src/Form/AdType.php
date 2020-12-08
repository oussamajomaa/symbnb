<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ApplicationType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TypeTextType::class, $this->attr("Titre de l'annonce", "Entrer le titre de l'annonce"))
            ->add('slug', TypeTextType::class, $this->attr("L'adresse web de l'annonce","Adresse web automatique", 
                [
                'disabled' => true
                ])
            )
            ->add('price', MoneyType::class, $this->attr('Prix','entrer le prix'))
            ->add('introduction')
            ->add('content')
            ->add('coverImage')
            ->add('rooms')
            ->add('images', CollectionType::class, [
                // les champs que je dois répéter
                'entry_type'    => ImageType::class,
                'allow_add'     => true,
                'allow_delete'  => true
            ])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
