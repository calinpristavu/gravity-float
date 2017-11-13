<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\User;
use AppBundle\Repository\ShopRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UserType
 *
 * @author: Enache Ioan Ovidiu <i.ovidiuenache@yahoo.com>
 */
class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'user.name',
            ])
            ->add('phone', TextType::class, [
                'label' => 'user.phone',
            ])
            ->add('email', EmailType::class, [
                'label' => 'user.email',
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'passwords.must.match',
                'required' => $options['isPasswordRequired'],
                'first_options' => ['label' => 'user.password.first'],
                'second_options' => ['label' => 'user.password.second'],
            ])
            ->add('canCreateOnlineVouchers', CheckboxType::class, [
                'required' => false,
                'label' => $options['createOnlineVouchersLabel'],
            ])
            ->add('shop', EntityType::class, [
                'class' => 'AppBundle:Shop',
                'query_builder' => function (ShopRepository $shopRepository) {
                    return $shopRepository->createQueryBuilder('s');
                },
                'choice_label' => 'name',
                'expanded' => true,
                'multiple' => false,
                'label' => false,
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Regular User' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN',
                ],
                'expanded' => true,
                'label' => false,
                'multiple' => true,
                'constraints' => [
                    new Assert\Count([
                        'min' => 1,
                        'minMessage' => 'Please select at least one option!',
                    ]),
                ],
            ])
            ->add('save', SubmitType::class, ['label' => "button.save"]);;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'isPasswordRequired' => true,
            'createOnlineVouchersLabel' => 'Can create online vouchers',
        ]);
    }
}
