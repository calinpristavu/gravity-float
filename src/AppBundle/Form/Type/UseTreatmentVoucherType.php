<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class VoucherDateType
 *
 * @author: Enache Ioan Ovidiu <i.ovidiuenache@yahoo.com>
 */
class UseTreatmentVoucherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("usageType", ChoiceType::class, [
                'choices' => [
                    'complete_use' => 'complete_use',
                ],
                'required' => true,
                'expanded' => true,
            ])
            ->add('info', TextareaType::class, [
                'required' => false,
            ])
        ;

        $now = new \DateTime();
        if ($options['expirationDate'] < $now) {
            $builder->add('confirmExtraCost', CheckboxType::class, [
                'required' => true,
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null,
            'expirationDate' => null,
        ));
    }
}
