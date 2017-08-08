<?php

namespace AppBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Payment
 * @author: Enache Ioan Ovidiu <i.ovidiuenache@yahoo.com>
 *
 * @ORM\Table(name="payments")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PaymentRepository")
 */
class Payment
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    protected $product;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $paymentDate;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    protected $amount;

    /**
     * Bidirectional - Many payments are created by one user (OWNING SIDE)
     *
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="createdPayments")
     */
    protected $employee;

    /**
     * Bidirectional - Many payments are made to one voucher (OWNING SIDE)
     *
     * @var Voucher
     *
     * @ORM\ManyToOne(targetEntity="Voucher", inversedBy="payments")
     */
    protected $voucherBought;

    /**
     * Payment constructor.
     */
    public function __construct()
    {
    }

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getProduct() : ?string
    {
        return $this->product;
    }

    public function setProduct(string $product) : self
    {
        $this->product = $product;

        return $this;
    }

    public function getPaymentDate() : ?\DateTime
    {
        return $this->paymentDate;
    }

    public function setPaymentDate(\DateTime $paymentDate) : self
    {
        $this->paymentDate = $paymentDate;

        return $this;
    }

    public function getAmount() : ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount) : self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getEmployee() : ?User
    {
        return $this->employee;
    }

    public function setEmployee(User $employee) : self
    {
        $this->employee = $employee;

        return $this;
    }

    public function getVoucherBought() : ?Voucher
    {
        return $this->voucherBought;
    }

    public function setVoucherBought(Voucher $voucherBought) : self
    {
        $this->voucherBought = $voucherBought;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function __toString()
    {
        return $this->getAmount() . '€ used on ' .
                $this->getProduct() . ' on ' .
                $this->getPaymentDate()->format('m/d/Y') . ' by employee ' .
                $this->getEmployee();
    }
}
