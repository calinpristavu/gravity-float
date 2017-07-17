<?php

namespace AppBundle\Service;
use AppBundle\Entity\Voucher;
use AppBundle\Repository\VoucherRepository;

/**
 * Class VoucherCodeGenerator
 * @author: Enache Ioan Ovidiu <i.ovidiuenache@yahoo.com>
 */
class VoucherCodeGenerator
{
    /**
     * @var VoucherRepository
     */
    protected $voucherRepository;

    /**
     * VoucherCodeGenerator constructor.
     *
     * @param VoucherRepository $voucherRepository
     */
    public function __construct(VoucherRepository $voucherRepository)
    {
        $this->voucherRepository = $voucherRepository;
    }

    /**
     * @param Voucher $voucher
     *
     * @return string
     */
    public function generateCodeForVoucher(Voucher $voucher)
    {
        $voucherCode = '';

        if ($voucher->isOnlineVoucher()) {
            $voucherCode = '222O';
        } else {
            switch ($voucher->getShopWhereCreated()->getName()) {
                case 'Shop A':
                    $voucherCode = '201A';
                    break;
                case 'Shop B':
                    $voucherCode = '202B';
                    break;
                case 'Shop C':
                    $voucherCode = '204C';
                    break;
                default:
                    break;
            }
        }

        $temp = rand(100000, 999999);
        while ($this->voucherRepository->findBy(['voucherCode' => $voucherCode.$temp]) != null) {
            $temp = rand(100000, 999999);
        }

        return $voucherCode.$temp;
    }
}
