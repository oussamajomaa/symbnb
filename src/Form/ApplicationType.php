<?php

namespace App\Form;
use Symfony\Component\Form\AbstractType;

class ApplicationType extends AbstractType
{
    protected function attr($label, $placeholder, $options = [])
    {
        return array_merge(

            [
                "label" => $label,
                "attr" => [
                    "placeholder" => $placeholder
                ]
            ],
            $options
        );
    }
}