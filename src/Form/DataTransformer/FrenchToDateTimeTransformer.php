<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

//pour que ça fonctionne bien, il faut implimenter la class DatatransformerInterface
class FrenchToDateTimeTransformer implements DataTransformerInterface {

    public function transform($date)
    {
        //on vérifie si la date n'est pas vide
        if ($date === null){
            return '';
        }
        return $date->format("d/m/Y");
    }

    public function reverseTransform($frenchDate)
    {
        //on vérifie si la date n'est pas vide
        if ($frenchDate === null){
            // cette exception est créée lorsqu'il y a un problème avec la transformation
            throw new TransformationFailedException();
        }

        //on crée un objet DateTime à partir d'une variable de format français
        $date = \DateTime::createFromFormat("d/m/Y",$frenchDate);

        //on va vérifier si le format français est bon
        if ($date === false){
            throw new TransformationFailedException();
        }

        return $date;
    }
}