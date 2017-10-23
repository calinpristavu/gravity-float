<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class VoucherDateType
 *
 * @author: Enache Ioan Ovidiu <i.ovidiuenache@yahoo.com>
 */
class UseValueVoucherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("usageType", ChoiceType::class, [
                'choices' => [
                    'complete_use' => 'complete_use',
                    'partial_use' => 'partial_use',
                ],
                'required' => true,
                'expanded' => true,
            ])
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
            ->add('info', TextareaType::class, [
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null,
            'remainingVoucherValue' => null,
        ));
    }
}
