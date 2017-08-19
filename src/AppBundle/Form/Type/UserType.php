<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\User;
use AppBundle\Repository\ShopRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UserType
 * @author: Enache Ioan Ovidiu <i.ovidiuenache@yahoo.com>
 */
class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('phone')
            ->add('email', EmailType::class)
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'passwords.must.match',
                'required' => $options['isPasswordRequired'],
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
            ))
            ->add('canCreateOnlineVouchers', CheckboxType::class, array(
                'required' => false,
                'label' => $options['createOnlineVouchersLabel']
            ))
            ->add('shop', EntityType::class, array(
                'class' => 'AppBundle:Shop',
                'query_builder' => function (ShopRepository $shopRepository) {
                    return $shopRepository->createQueryBuilder('s');
                },
                'choice_label' => 'name',
                'expanded' => true,
                'multiple' => false,
                'label' => false,
            ))
            ->add('roles', ChoiceType::class, array(
                'choices'  => array(
                    'regular.user' => 'ROLE_USER',
                    'admin' => 'ROLE_ADMIN',
                ),
                'expanded' => true,
                'label' => false,
                'multiple' => true,
            ))
            ->add('save', SubmitType::class, array('label' => "button.save"));
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
            'isPasswordRequired' => true,
            'createOnlineVouchersLabel' => 'Can create online vouchers',
        ));
    }
}
