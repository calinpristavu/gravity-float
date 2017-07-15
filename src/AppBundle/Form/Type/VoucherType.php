<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Voucher;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

/**
 * Class VoucherType
 *
 * @author: Enache Ioan Ovidiu <i.ovidiuenache@yahoo.com>
 */
class VoucherType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('onlineVoucher', CheckboxType::class, array(
                'required' => false,
            ))
            ->add('originalValue', NumberType::class)
            ->add('numberOfUsers', ChoiceType::class, array(
                'choices' => array(
                    'Single' => 'single',
                    'Couple' => 'couple',
                ),
                'expanded' => true,
                'multiple' => false,
            ))
            ->add('usages', ChoiceType::class, array(
                'choices' => array(
                    'Massage' => 'massage',
                    'Floating' => 'floating',
                    'Value Voucher' => 'valueVoucher',
                ),
                'expanded' => true,
                'multiple' => true,
            ))
            ->add('methodsOfPayment', ChoiceType::class, array(
                'choices' => array(
                    'Card' => 'card',
                    'Cash' => 'cash',
                    'Paypal' => 'paypal',
                ),
                'expanded' => true,
                'multiple' => true,
            ))
            ->add("expirationDate", DateTimeType::class, [
                'widget' => "single_text"
            ])
            ->add('create', SubmitType::class, array('label' => "button.create"))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Voucher::class,
        ));
    }
}
