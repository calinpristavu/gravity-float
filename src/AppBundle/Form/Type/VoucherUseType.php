<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class VoucherUseType
 *
 * @author: Enache Ioan Ovidiu <i.ovidiuenache@yahoo.com>
 */
class VoucherUseType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = array();
        foreach ($options['voucherUsages'] as $usage) {
            if (strpos(strtolower($usage), 'massage') !== false) {
                $choices[ucwords($usage)] = 'USE_FOR_MASSAGE';
            } else {
                $choices[ucwords($usage)] = 'USE_FOR_FLOAT';
            }
        }

        $builder
            ->add('usage', ChoiceType::class, array (
                'choices' => array (
                    'complete.use' => 'COMPLETE_USE',
                    'partial.use' => 'PARTIAL_USE',
                ),
                'multiple' => false,
                'expanded' => true,
                'required' => true,
            ))
            ->add('used_for', ChoiceType::class, array (
                'choices' => $choices,
                'multiple' => true,
                'expanded' => true,
                'label' => 'use.for',
                'constraints' => [
                    new Assert\Count(array(
                        'min' => 1,
                        'minMessage' => 'Please select at least one option!',
                    ))
                ]
            ))
            ->add('partial_amount', NumberType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Range(array(
                        'min'        => 1,
                        'max'        => $options['remainingVoucherValue'],
                        'minMessage' => 'Invalid value! Please enter a bigger amount!',
                        'maxMessage' => 'Invalid value! You cannot use more than the remaining voucher value',
                    ))
                ]
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null,
            'voucherUsages' => null,
            'error_bubbling' => true,
            'remainingVoucherValue' => null,
        ));
    }
}
