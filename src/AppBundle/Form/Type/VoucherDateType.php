<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ->add('created_at', ChoiceType::class, array (
                'choices' => array (
                    date("Y") => date("Y"),
                    date("Y", strtotime("-1 year")) => date("Y", strtotime("-1 year")),
                    date("Y", strtotime("-2 year")) => date("Y", strtotime("-2 year")),
                ),
                'multiple' => true,
                'expanded' => true,
            ))
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
