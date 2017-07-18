<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Voucher
 * @author: Enache Ioan Ovidiu <i.ovidiuenache@yahoo.com>
 *
 * @ORM\Table(name="vouchers")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VoucherRepository")
 */
class Voucher
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     */
    protected $voucherCode;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $creationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $expirationDate;

    /**
     * Bidirectional - Many vouchers are authored by one user (OWNING SIDE)
     *
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="createdVouchers")
     */
    protected $author;

    /**
     * Bidirectional - Many ideas are created at one shop (OWNING SIDE)
     *
     * @var Shop
     *
     * @ORM\ManyToOne(targetEntity="Shop", inversedBy="createdVouchers")
     */
    protected $shopWhereCreated;

    /**
     * Bidirectional - Many ideas are created for one customer (OWNING SIDE)
     *
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="designatedVouchers")
     */
    protected $designatedCustomer;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    protected $remainingValue;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    protected $partialPayment;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     */
    protected $numberOfUsers;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $onlineVoucher;

    /**
     * Array of string representing methods of payment
     *
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $methodOfPayment;

    /**
     * Array of string representing the facilities it can be used for
     *
     * @var array
     *
     * @ORM\Column(type="array")
     */
    protected $usages;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable = true)
     */
    protected $orderNumber;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable = true)
     */
    protected $invoiceNumber;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable = true)
     */
    protected $includedPostalCharges;

    /**
     * Bidirectional - One-To-Many (INVERSE SIDE)
     * One Voucher can have many payments
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Payment", mappedBy="voucherBought")
     */
    protected $payments;

    public function __construct()
    {
        $this->payments = new ArrayCollection();
        $this->usages = array();
        $this->numberOfUsers = array();

        $this->partialPayment = 0;
        $this->orderNumber = "";
        $this->invoiceNumber = "";
        $this->includedPostalCharges = false;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @return \DateTime
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    /**
     * @param \DateTime $expirationDate
     *
     * @return $this
     */
    public function setExpirationDate($expirationDate)
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    /**
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param User $author
     *
     * @return $this
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Shop
     */
    public function getShopWhereCreated()
    {
        return $this->shopWhereCreated;
    }

    /**
     * @param Shop $shopWhereCreated
     *
     * @return $this
     */
    public function setShopWhereCreated($shopWhereCreated)
    {
        $this->shopWhereCreated = $shopWhereCreated;

        return $this;
    }

    /**
     * @return User
     */
    public function getDesignatedCustomer()
    {
        return $this->designatedCustomer;
    }

    /**
     * @param User $designatedCustomer
     *
     * @return $this
     */
    public function setDesignatedCustomer($designatedCustomer)
    {
        $this->designatedCustomer = $designatedCustomer;

        return $this;
    }

    /**
     * @return float
     */
    public function getRemainingValue()
    {
        return $this->remainingValue;
    }

    /**
     * @param float $remainingValue
     *
     * @return $this
     */
    public function setRemainingValue($remainingValue)
    {
        $this->remainingValue = $remainingValue;

        return $this;
    }

    /**
     * @return float
     */
    public function getPartialPayment()
    {
        return $this->partialPayment;
    }

    /**
     * @param float $partialPayment
     *
     * @return $this
     */
    public function setPartialPayment($partialPayment)
    {
        $this->partialPayment = $partialPayment;

        return $this;
    }

    /**
     * @return array
     */
    public function getNumberOfUsers()
    {
        return $this->numberOfUsers;
    }

    /**
     * @param array $numberOfUsers
     *
     * @return $this
     */
    public function setNumberOfUsers($numberOfUsers)
    {
        $this->numberOfUsers = $numberOfUsers;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isOnlineVoucher()
    {
        return $this->onlineVoucher;
    }

    /**
     * @param boolean $onlineVoucher
     *
     * @return $this
     */
    public function setOnlineVoucher($onlineVoucher)
    {
        $this->onlineVoucher = $onlineVoucher;

        return $this;
    }

    /**
     * @return string
     */
    public function getMethodOfPayment()
    {
        return $this->methodOfPayment;
    }

    /**
     * @return array
     */
    public function getUsages()
    {
        return $this->usages;
    }

    /**
     * @param string $methodOfPayment
     *
     * @return $this
     */
    public function setMethodOfPayment($methodOfPayment)
    {
        $this->methodOfPayment = $methodOfPayment;

        return $this;
    }

    /**
     * @param array $usages
     *
     * @return $this
     */
    public function setUsages($usages)
    {
        $this->usages = $usages;

        return $this;
    }

    /**
     * @return string
     */
    public function getVoucherCode()
    {
        return $this->voucherCode;
    }

    /**
     * @param string $voucherCode
     *
     * @return $this
     */
    public function setVoucherCode($voucherCode)
    {
        $this->voucherCode = $voucherCode;

        return $this;
    }

    /**
     * @param \DateTime $creationDate
     *
     * @return $this
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    /**
     * @param string $orderNumber
     */
    public function setOrderNumber($orderNumber)
    {
        $this->orderNumber = $orderNumber;
    }

    /**
     * @return string
     */
    public function getInvoiceNumber()
    {
        return $this->invoiceNumber;
    }

    /**
     * @param string $invoiceNumber
     */
    public function setInvoiceNumber($invoiceNumber)
    {
        $this->invoiceNumber = $invoiceNumber;
    }

    /**
     * @return boolean
     */
    public function isIncludedPostalCharges()
    {
        return $this->includedPostalCharges;
    }

    /**
     * @param boolean $includedPostalCharges
     */
    public function setIncludedPostalCharges($includedPostalCharges)
    {
        $this->includedPostalCharges = $includedPostalCharges;
    }

    /**
     * @return ArrayCollection
     */
    public function getPayments()
    {
        return $this->payments;
    }

    /**
     * @param Payment $payment
     *
     * @return $this
     */
    public function addPayment($payment)
    {
        $this->payments->add($payment);

        return $this;
    }

    /**
     * @param Payment $payment
     *
     * @return $this
     */
    public function removePayment($payment)
    {
        $this->payments->removeElement($payment);

        return $this;
    }
}
