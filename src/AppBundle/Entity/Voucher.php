<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $blocked;

    /**
     * Bidirectional - One-To-Many (INVERSE SIDE)
     * One Voucher can have many payments
     *
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Payment", mappedBy="voucherBought")
     */
    protected $payments;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable = true)
     */
    protected $comment;

    public function __construct()
    {
        $this->payments = new ArrayCollection();
        $this->usages = array();
        $this->numberOfUsers = array();

        $this->partialPayment = 0;
        $this->orderNumber = "";
        $this->invoiceNumber = "";
        $this->comment = "";
        $this->includedPostalCharges = false;
        $this->blocked = false;
    }

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getCreationDate(): ?\DateTime
    {
        return $this->creationDate;
    }

    public function getExpirationDate() : ?\DateTime
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(\DateTime $expirationDate) : self
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    public function getAuthor() : ?User
    {
        return $this->author;
    }

    public function setAuthor(User $author) : self
    {
        $this->author = $author;

        return $this;
    }

    public function getShopWhereCreated() : ?Shop
    {
        return $this->shopWhereCreated;
    }

    public function setShopWhereCreated(Shop $shopWhereCreated) : self
    {
        $this->shopWhereCreated = $shopWhereCreated;

        return $this;
    }

    public function getRemainingValue() : ?float
    {
        return $this->remainingValue;
    }

    public function setRemainingValue(float $remainingValue) : self
    {
        $this->remainingValue = $remainingValue;

        return $this;
    }

    public function getPartialPayment() : ?float
    {
        return $this->partialPayment;
    }

    public function setPartialPayment(float $partialPayment) : self
    {
        $this->partialPayment = $partialPayment;

        return $this;
    }

    public function getNumberOfUsers() : ?array
    {
        return $this->numberOfUsers;
    }

    public function setNumberOfUsers(array $numberOfUsers) : self
    {
        $this->numberOfUsers = $numberOfUsers;

        return $this;
    }

    public function isOnlineVoucher() : ?bool
    {
        return $this->onlineVoucher;
    }

    public function setOnlineVoucher(bool $onlineVoucher) : self
    {
        $this->onlineVoucher = $onlineVoucher;

        return $this;
    }

    public function getMethodOfPayment() : ?string
    {
        return $this->methodOfPayment;
    }

    public function getUsages() : ?array
    {
        return $this->usages;
    }

    public function setMethodOfPayment(string $methodOfPayment) : self
    {
        $this->methodOfPayment = $methodOfPayment;

        return $this;
    }

    public function setUsages(array $usages) : self
    {
        $this->usages = $usages;

        return $this;
    }

    public function getVoucherCode() : ?string
    {
        return $this->voucherCode;
    }

    public function setVoucherCode(string $voucherCode) : self
    {
        $this->voucherCode = $voucherCode;

        return $this;
    }

    public function setCreationDate(\DateTime $creationDate) : self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getOrderNumber() : ?string
    {
        return $this->orderNumber;
    }

    public function setOrderNumber($orderNumber) : self
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    public function getInvoiceNumber() : ?string
    {
        return $this->invoiceNumber;
    }

    public function setInvoiceNumber(string $invoiceNumber) : self
    {
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }

    public function isIncludedPostalCharges() : ?bool
    {
        return $this->includedPostalCharges;
    }

    public function setIncludedPostalCharges(bool $includedPostalCharges) : self
    {
        $this->includedPostalCharges = $includedPostalCharges;

        return $this;
    }

    public function getPayments() : Collection
    {
        return $this->payments;
    }

    public function addPayment(Payment $payment)  : self
    {
        $this->payments->add($payment);

        return $this;
    }

    public function removePayment(Payment $payment) : self
    {
        $this->payments->removeElement($payment);

        return $this;
    }

    public function isBlocked() : ?bool
    {
        return $this->blocked;
    }

    public function setBlocked(bool $blocked) : self
    {
        $this->blocked = $blocked;

        return $this;
    }

    public function getComment() : ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment) : self
    {
        $this->comment = $comment;

        return $this;
    }
}
