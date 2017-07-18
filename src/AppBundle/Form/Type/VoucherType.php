<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Voucher;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            ->add('orderNumber', TextType::class ,array(
                'required' => false,
            ))
            ->add('invoiceNumber', TextType::class, array(
                'required' => false,
            ))
            ->add('includedPostalCharges', CheckboxType::class, array(
                'required' => false,
            ))
            ->add('remainingValue', NumberType::class, array(
                'label' => 'Value'
            ))
            ->add('numberOfUsers', ChoiceType::class, array(
                'choices' => array(
                    'Single' => 'Single',
                    'Couple' => 'Couple',
                ),
                'expanded' => true,
                'multiple' => true,
            ))
            ->add('usages', ChoiceType::class, array(
                'choices' => array(
                    'Massage' => 'massage',
                    'Floating' => 'floating',
                ),
                'expanded' => true,
                'multiple' => true,
            ))
            ->add('massage_type', ChoiceType::class, array(
                'choices' => array(
                    'Classic' => 'Classic',
                    'Deep Relax' => 'Deep Relax',
                ),
                'mapped' => false,
                'expanded' => true,
                'required' => false,
                'placeholder' => false,
            ))
            ->add('time_for_massage', ChoiceType::class, array(
                'choices' => array(
                    '30 Minutes' => '30 minutes',
                    '45 Minutes' => '45 minutes',
                    '60 Minutes' => '60 minutes',
                    '90 Minutes' => '90 minutes',
                ),
                'mapped' => false,
                'required' => false,
                'placeholder' => false,
            ))
            ->add('time_for_floating', ChoiceType::class, array(
                'choices' => array(
                    '60 Minutes' => '60_minutes',
                ),
                'mapped' => false,
                'required' => false,
                'placeholder' => false,
            ))
            ->add('methodOfPayment', ChoiceType::class, array(
                'choices' => array(
                    'Card' => 'Card',
                    'Cash' => 'Cash',
                    'Paypal' => 'Paypal',
                ),
                'expanded' => true,
                'multiple' => false,
            ))
            ->add("expirationDate", DateTimeType::class, [
                'widget' => "single_text",
                'data' => new \DateTime("+3 year")
            ])
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
