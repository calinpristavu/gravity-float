<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User
 * @author: Enache Ioan Ovidiu <i.ovidiuenache@yahoo.com>
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class User extends BaseUser
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
     * @ORM\Column(type="string", length=40)
     * @Assert\Length(
     *     max = 40,
     *     maxMessage = "name.too.long"
     * )
     * @Serializer\Expose()
     */
    protected $name;

    /**
     * Bidirectional - Many users are assigned to one shop (OWNING SIDE)
     *
     * @var Shop
     *
     * @ORM\ManyToOne(targetEntity="Shop", inversedBy="assignedUsers")
     * @Serializer\Expose()
     */
    protected $shop;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     * @Serializer\Expose()
     */
    protected $canCreateOnlineVouchers;

    /**
     * Bidirectional - One-To-Many (INVERSE SIDE)
     * One User can create many vouchers
     *
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Voucher", mappedBy="author")
     */
    protected $createdVouchers;

    /**
     * Bidirectional - One-To-Many (INVERSE SIDE)
     * One User can create many payments
     *
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Payment", mappedBy="employee")
     */
    protected $createdPayments;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=15)
     * @Assert\Length(
     *     max = 15,
     *     maxMessage = "phone.too.long"
     * )
     */
    protected $phone;

    public function __construct()
    {
        parent::__construct();

        $this->createdVouchers = new ArrayCollection();
        $this->createdPayments = new ArrayCollection();

        $this->name = "";
        $this->phone = "";
        $this->canCreateOnlineVouchers = false;
    }

    public function getCanCreateOnlineVouchers() : bool
    {
        return $this->canCreateOnlineVouchers;
    }

    public function setCanCreateOnlineVouchers(bool $canCreateVouchers) : self
    {
        $this->canCreateOnlineVouchers = $canCreateVouchers;

        return $this;
    }

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function setName(string $name) : self
    {
        $this->name = $name;

        return $this;
    }

    public function getShop() : ?Shop
    {
        return $this->shop;
    }

    public function setShop(Shop $shop) : self
    {
        $this->shop = $shop;

        return $this;
    }

    public function getPhone() : string
    {
        return $this->phone;
    }

    public function setPhone(string $phone) : self
    {
        $this->phone = $phone;

        return $this;
    }

    public function __toString() : ?string
    {
        return $this->getName();
    }

    /**
     * Username is the same as the email
     */
    public function getUsername() : ?string
    {
        return $this->getEmail();
    }

    /**
     * Username is the same as the email
     */
    public function getUsernameCanonical() : ?string
    {
        return $this->getEmailCanonical();
    }

    public function getCreatedVouchers() : Collection
    {
        return $this->createdVouchers;
    }

    public function addCreatedVoucher(Voucher $voucher)  : self
    {
        $this->createdVouchers->add($voucher);

        return $this;
    }

    public function removeCreatedVoucher(Voucher $voucher) : self
    {
        $this->createdVouchers->removeElement($voucher);

        return $this;
    }

    public function setEmail($email) : self
    {
        parent::setEmail($email);

        $this->setUsername($email);

        return $this;
    }

    public function setEmailCanonical($email) : self
    {
        parent::setEmailCanonical($email);

        $this->setUsernameCanonical($email);

        return $this;
    }

    public function getCreatedPayments() : Collection
    {
        return $this->createdPayments;
    }

    public function addCreatedPayment(Payment $payment) : self
    {
        $this->createdPayments->add($payment);

        return $this;
    }

    public function removeCreatedPayment(Payment $payment) : self
    {
        $this->createdPayments->removeElement($payment);

        return $this;
    }
}
