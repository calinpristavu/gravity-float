<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\AvailableService;
use AppBundle\Entity\User;
use AppBundle\Entity\Voucher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TreatmentVoucherType extends AbstractType
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('voucherCodeLetter', CodeLetterType::class)
            ->add('service', EntityType::class, [
                'class' => AvailableService::class,
                'choice_attr' => function (AvailableService $service) {
                    return [
                        'data-price' => $service->getPrice()
                    ];
                },
                'expanded' => true,
                'multiple' => false
            ])
            ->add('methodOfPayment', PaymentMethodType::class)
            ->add("expirationDate", DateType::class, [
                'widget' => "single_text",
                'format' => 'dd/MM/yyyy',
                'attr' => [
                    'class' => 'datepicker'
                ],
                'data' => (new \DateTime('31-12-'.(date('Y') + 3)))
            ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            if ($this->getUser()->getCanCreateOnlineVouchers()) {
                $form = $event->getForm();

                $form
                    ->add('onlineVoucher', CheckboxType::class, [
                        'required' => false,
                    ])
                    ->add('orderNumber', TextType::class, [
                        'required' => false,
                    ])
                    ->add('invoiceNumber', TextType::class, [
                        'required' => false,
                    ])
                    ->add('includedPostalCharges', CheckboxType::class, [
                        'required' => false,
                    ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Voucher::class
        ]);
    }

    private function getUser(): ?User
    {
        return $this->tokenStorage->getToken()->getUser();
    }
}
