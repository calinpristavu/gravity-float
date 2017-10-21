<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentMethodType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choices' => [
                'Card' => 'Card',
                'Cash' => 'Cash',
                'Paypal' => 'Paypal',
                'Lastschrift' => 'Lastschrift'
            ],
            'expanded' => true,
            'multiple' => false,
            'required' => true
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }

}
