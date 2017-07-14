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
     * Bidirectional - Many ideas are authored by one user (OWNING SIDE)
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
     * @var double
     *
     * @ORM\Column(type="double")
     */
    protected $originalValue;

    /**
     * @var double
     *
     * @ORM\Column(type="double")
     */
    protected $partialPayment;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $numberOfUsers;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", length=50)
     */
    protected $onlineVoucher;

    /**
     * Array of string representing methods of payment
     *
     * @var ArrayCollection
     *
     * @ORM\Column(type="array")
     */
    protected $methodsOfPayment;

    /**
     * Array of string representing the facilities it can be used for
     *
     * @var ArrayCollection
     *
     * @ORM\Column(type="array")
     */
    protected $usages;

    public function __construct()
    {
        parent::__construct();

        $this->usages = new ArrayCollection();
        $this->methodsOfPayment = new ArrayCollection();
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
    public function getOriginalValue()
    {
        return $this->originalValue;
    }

    /**
     * @param float $originalValue
     *
     * @return $this
     */
    public function setOriginalValue($originalValue)
    {
        $this->originalValue = $originalValue;

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
     * @return string
     */
    public function getNumberOfUsers()
    {
        return $this->numberOfUsers;
    }

    /**
     * @param string $numberOfUsers
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
     * @return ArrayCollection
     */
    public function getMethodsOfPayment()
    {
        return $this->methodsOfPayment;
    }

    /**
     * @param string $methodOfPayment
     *
     * @return $this
     */
    public function addMethodOfPayment($methodOfPayment)
    {
        $this->methodsOfPayment->add($methodOfPayment);

        return $this;
    }

    /**
     * @param string $methodOfPayment
     *
     * @return $this
     */
    public function removeMethodOfPayment($methodOfPayment)
    {
        $this->methodsOfPayment->removeElement($methodOfPayment);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getUsages()
    {
        return $this->usages;
    }

    /**
     * @param string $usage
     *
     * @return $this
     */
    public function addUsage($usage)
    {
        $this->usages->add($usage);

        return $this;
    }

    /**
     * @param string $usage
     *
     * @return $this
     */
    public function removeUsage($usage)
    {
        $this->usages->removeElement($usage);

        return $this;
    }
}
