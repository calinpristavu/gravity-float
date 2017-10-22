<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class UseValueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('complete_use', ChoiceType::class, [
                'data' => true,
                'choices' => [
                    "complete.use" => true,
                    "partial.use" => false
                ],
                'multiple' => false,
                'expanded' => true
            ])
            ->add('partial_amount', NumberType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Range(array(
                        'min' => 1,
                        'max' => $options['remainingVoucherValue'],
                        'minMessage' => 'Invalid value! Please enter a bigger amount!',
                        'maxMessage' => 'Invalid value! You cannot use more than the remaining voucher value',
                    ))
                ]
            ])
            ->add('info', TextareaType::class, [
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
            'error_bubbling' => true,
            'remainingVoucherValue' => null,
        ]);
    }

}
