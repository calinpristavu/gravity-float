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
                'payment.card' => 'Card',
                'payment.cash' => 'Cash',
                'payment.paypal' => 'Paypal',
                'payment.lastschrift' => 'Lastschrift'
            ],
            'expanded' => true,
            'multiple' => false,
            'required' => true,
            'label' => 'voucher.method.of.payment',
            'choice_translation_domain' => true
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }

}
