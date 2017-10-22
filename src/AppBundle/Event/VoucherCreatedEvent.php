<?php

namespace AppBundle\Event;

use AppBundle\Entity\Voucher;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Form\FormInterface;

class VoucherCreatedEvent extends Event
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

    public function setVoucher(Voucher $voucher): self
    {
        $this->voucher = $voucher;

        return $this;
    }
}
