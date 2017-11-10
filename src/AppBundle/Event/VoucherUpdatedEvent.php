<?php

namespace AppBundle\Event;

use AppBundle\Entity\Voucher;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Form\FormInterface;

class VoucherUpdatedEvent extends Event implements VoucherEventInterface
{
    protected $voucher;

    protected $form;

    public function __construct(Voucher $voucher, FormInterface $form)
    {
        $this->voucher = $voucher;
        $this->form = $form;
    }

    public function getVoucher(): Voucher
    {
        return $this->voucher;
    }

    public function getForm(): FormInterface
    {
        return $this->form;
    }
}
