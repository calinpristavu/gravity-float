<?php

namespace AppBundle\EventSubscriber;

use AppBundle\Entity\User;
use AppBundle\Event\AppEvents;
use AppBundle\Event\VoucherCreatedEvent;
use AppBundle\Repository\VoucherCodeInformationRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class VoucherCodeGeneratorSubscriber implements EventSubscriberInterface
{
    private $codeInformationRepo;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(
        VoucherCodeInformationRepository $codeInformationRepo,
        TokenStorageInterface $tokenStorage
    ) {
        $this->codeInformationRepo = $codeInformationRepo;
        $this->tokenStorage = $tokenStorage;
    }

    public static function getSubscribedEvents()
    {
        return [
            AppEvents::VOUCHER_CREATED => [
                ['setUserRelatedInfo', 10],
                ['setVoucherCode', -10],
                ['setVoucherValue', -20],
            ]
        ];
    }

    public function setVoucherCode(VoucherCreatedEvent $event)
    {
        $voucher = $event->getVoucher();
        $form = $event->getForm();

        $shopToCodeMap = [
            0 => 222,
            1 => 201,
            2 => 204,
            3 => 205
        ];

        $shopId = $voucher->getShopWhereCreated()->getId();
        if ($voucher->isOnlineVoucher()) {
            $shopId = 0;
        }

        $voucherCodeInfo = $this->codeInformationRepo->find($shopId);

        $voucher->setVoucherCode(
            $shopToCodeMap[$shopId] .
            $form->get('voucherCodeLetter')->getData() .
            $voucherCodeInfo->getNextVoucherCode()
        );
    }

    public function setVoucherValue(VoucherCreatedEvent $event)
    {
        $voucher = $event->getVoucher();

        if ($voucher->getType()->getId() !== 2) {
            return;
        }

        $voucher->setRemainingValue($voucher->getService()->getPrice());
    }

    public function setUserRelatedInfo(VoucherCreatedEvent $event)
    {
        $user = $this->getuser();
        $event
            ->getVoucher()
            ->setBlocked(false)
            ->setShopWhereCreated($user->getShop())
            ->setAuthor($user);
    }

    private function getuser(): User
    {
        return $this->tokenStorage->getToken()->getUser();
    }
}
