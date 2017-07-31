<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class VoucherCodeInformation
 * @author: Enache Ioan Ovidiu <i.ovidiuenache@yahoo.com>
 *
 * @ORM\Table(name="voucher_code_information")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VoucherRepository")
 */
class VoucherCodeInformation
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $shopId;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $voucherCode;

    /**
     * @return int
     */
    public function getNextVoucherCode()
    {
        return $this->voucherCode++;
    }
}
