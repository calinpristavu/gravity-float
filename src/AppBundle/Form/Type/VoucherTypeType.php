<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Voucher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoucherTypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', EntityType::class, [
                'class' => \AppBundle\Entity\VoucherType::class,
                'label' => false,
                'multiple' => false,
                'expanded' => true,
                'choice_translation_domain' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Voucher::class,
        ]);
    }

}
