<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CodeLetterType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choices' => array(
                'A' => 'A',
                'C' => 'C',
                'G' => 'G',
                'O' => 'O',
            ),
            'expanded' => false,
            'multiple' => false,
            'required' => true,
            'mapped' => false,
            'label' => 'voucher.code_letter',
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}
