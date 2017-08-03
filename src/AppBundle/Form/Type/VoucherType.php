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
                    'Single (default value)' => 'Single',
                    'Couple' => 'Couple',
                ),
                'expanded' => true,
                'multiple' => true,
                'required' => true,
                'empty_data' => 'Single',
            ))
            ->add('usages', ChoiceType::class, array(
                'choices' => array(
                    'Massage (default value)' => 'massage',
                    'Floating' => 'floating',
                ),
                'expanded' => true,
                'multiple' => true,
                'required' => true,
                'empty_data' => 'massage',
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
                'data' => '30 minutes',
            ))
            ->add('time_for_floating', ChoiceType::class, array(
                'choices' => array(
                    '60 Minutes' => '60 minutes',
                ),
                'mapped' => false,
                'required' => false,
                'placeholder' => false,
                'data' => '60 minutes',
            ))
            ->add('methodOfPayment', ChoiceType::class, array(
                'choices' => array(
                    'Card' => 'Card',
                    'Cash' => 'Cash',
                    'Paypal' => 'Paypal',
                ),
                'expanded' => true,
                'multiple' => false,
                'required' => true,
            ))
            ->add('voucherCodeLetter', ChoiceType::class, array(
                'choices' => array (
                    'A' => 'A',
                    'C' => 'C',
                    'G' => 'G',
                    'O' => 'O',
                ),
                'expanded' => false,
                'multiple' => false,
                'required' => true,
                'mapped' => false,
                'label' => 'Voucher Type',
            ))
            ->add("expirationDate", DateTimeType::class, [
                'widget' => "single_text",
                'format' => 'dd/MM/yyyy',
                'attr' => [
                    'class' => 'datepicker'
                ],
                'data' => new \DateTime(date("Y")."-12-31 +3 years")
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
