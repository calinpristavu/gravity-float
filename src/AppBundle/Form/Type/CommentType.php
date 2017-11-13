<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Voucher;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CommentType
 * @author: Enache Ioan Ovidiu <i.ovidiuenache@yahoo.com>
 */
class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comment', TextareaType::class, [
                'label' => 'voucher.comment',
                'attr' => array('maxlength' => 300)
            ])
            ->add('save', SubmitType::class, [
                'label' => "button.save"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Voucher::class,
        ));
    }
}
