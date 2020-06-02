<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;

class ApplicationType extends AbstractType
{
    /**
     * Permet d'avoir la configuration de base d'un champ
     * @param string $placeholder
     * @param array $options
     * @return array
     */
    protected function getConfiguration($placeholder, $options = ['required' => true]){
        return array_merge_recursive([
            'attr' => [
                'placeholder' => $placeholder,
            ]
        ], $options);
    }

}