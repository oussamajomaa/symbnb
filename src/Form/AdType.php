<?php

namespace App\Form;

use App\Entity\Ad;
use Doctrine\DBAL\Types\FloatType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdType extends AbstractType
{
    private function attr($label,$placeholder){
        return  [
                "label"=>$label,
                "attr"=>[
                    "placeholder"=>$placeholder
                ]
            ];
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TypeTextType::class, [
                "label"=>"Titre de l'annonce",
                "attr"=>[
                    "placeholder"=>"entrer le titre de l'annonce"
                ]
            ])
            ->add('slug')
            ->add('price', MoneyType::class, $this->attr('Prix','entrer le prix'))
            ->add('introduction')
            ->add('content')
            ->add('coverImage')
            ->add('rooms')
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
