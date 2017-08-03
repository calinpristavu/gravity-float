<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class VoucherDateType
 * @author: Enache Ioan Ovidiu <i.ovidiuenache@yahoo.com>
 */
class VoucherDateType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("filterFrom", DateType::class, [
                'widget' => "single_text",
                'format' => 'dd/MM/yyyy',
                'label' => 'From',
                'attr' => [
                    'class' => 'datepicker'
                ],
                'required' => false,
            ])
            ->add("filterTo", DateType::class, [
                'widget' => "single_text",
                'format' => 'dd/MM/yyyy',
                'label' => 'To',
                'attr' => [
                    'class' => 'datepicker'
                ],
                'required' => false,
            ])
            ->add('search', SubmitType::class, array('label' => "button.search"))
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
