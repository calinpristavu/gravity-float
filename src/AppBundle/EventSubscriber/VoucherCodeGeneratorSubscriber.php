<?php

namespace AppBundle\EventSubscriber;

use AppBundle\Entity\User;
use AppBundle\Entity\VoucherCodeInformation;
use AppBundle\Event\AppEvents;
use AppBundle\Event\VoucherEventInterface;
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
            ],
            AppEvents::VOUCHER_UPDATED => [
                ['setVoucherValue', -20],
                ['setVoucherCode', -10],
            ]
        ];
    }

    public function setVoucherCode(VoucherEventInterface $event)
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

        /** @var VoucherCodeInformation $voucherCodeInfo */
        $voucherCodeInfo = $this->codeInformationRepo->find($shopId);
        $voucherNumber = $voucher->getVoucherCode()
            ? substr($voucher->getVoucherCode(), -5)
            : str_pad(
                (string) $voucherCodeInfo->getNextVoucherCode(),
                5,
                '0',
                STR_PAD_LEFT
            );

        $voucher->setVoucherCode(
            $shopToCodeMap[$shopId] .
            $form->get('voucherCodeLetter')->getData() .
            $voucherNumber
        );
    }

    public function setVoucherValue(VoucherEventInterface $event)
    {
        $voucher = $event->getVoucher();

        if ($voucher->getType()->getId() !== 2) {
            return;
        }

        $voucher->setRemainingValue($voucher->getService()->getPrice());
    }

    public function setUserRelatedInfo(VoucherEventInterface $event)
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
