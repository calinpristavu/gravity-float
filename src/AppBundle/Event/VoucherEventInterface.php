<?php

namespace AppBundle\Event;

use Symfony\Component\Form\FormInterface;
use AppBundle\Entity\Voucher;

interface VoucherEventInterface
{
    public function getVoucher(): Voucher;

    public function getForm(): FormInterface;
}
