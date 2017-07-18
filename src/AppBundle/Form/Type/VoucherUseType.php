<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
                'choices' => array (
                    'massage' => 'USE_FOR_MASSAGE',
                    'float' => 'USE_FOR_FLOAT',
                ),
                'multiple' => true,
                'expanded' => true,
                'required' => true,
                'label' => 'use.for'
            ))
            ->add('partial_amount', MoneyType::class, [
                'required' => false,
            ])
            ->add('use', SubmitType::class, array('label' => "use"))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null,
        ));
    }
}
