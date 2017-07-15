<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User
 * @author: Enache Ioan Ovidiu <i.ovidiuenache@yahoo.com>
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
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
     */
    protected $name;

    /**
     * Bidirectional - Many users are assigned to many shops (OWNING SIDE)
     *
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Shop", inversedBy="assignedUsers")
     * @ORM\JoinTable(name="users_shops")
     */
    protected $shops;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $canCreateVouchers;

    /**
     * Bidirectional - One-To-Many (INVERSE SIDE)
     * One User can create many vouchers
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Voucher", mappedBy="author")
     */
    protected $createdVouchers;

    /**
     * Bidirectional - One-To-Many (INVERSE SIDE)
     * One User can be designated many vouchers
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Voucher", mappedBy="designatedCustomer")
     */
    protected $designatedVouchers;

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
        $this->designatedVouchers = new ArrayCollection();
        $this->shops = new ArrayCollection();

        $this->name = "";
        $this->phone = "";
        $this->canCreateVouchers = false;
    }

    /**
     * @return boolean
     */
    public function getCanCreateVouchers()
    {
        return $this->canCreateVouchers;
    }

    /**
     * @param boolean $canCreateVouchers
     *
     * @return $this
     */
    public function setCanCreateVouchers($canCreateVouchers)
    {
        $this->canCreateVouchers = $canCreateVouchers;

        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getShops()
    {
        return $this->shops;
    }

    /**
     * @param Shop $shop
     *
     * @return $this
     */
    public function addShop($shop)
    {
        $this->shops->add($shop);

        return $this;
    }

    /**
     * @param Shop $shop
     *
     * @return $this
     */
    public function removeShop($shop)
    {
        $this->shops->removeElement($shop);

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     *
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        //Username is the same as the email
        return $this->getEmail();
    }

    /**
     * @return string
     */
    public function getUsernameCanonical()
    {
        //Username is the same as the email
        return $this->getEmailCanonical();
    }

    /**
     * @return ArrayCollection
     */
    public function getCreatedVouchers()
    {
        return $this->createdVouchers;
    }

    /**
     * @param Voucher $voucher
     *
     * @return $this
     */
    public function addCreatedVoucher($voucher)
    {
        $this->createdVouchers->add($voucher);

        return $this;
    }

    /**
     * @param Voucher $voucher
     *
     * @return $this
     */
    public function removeCreatedVoucher($voucher)
    {
        $this->createdVouchers->removeElement($voucher);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getDesignatedVouchers()
    {
        return $this->designatedVouchers;
    }

    /**
     * @param Voucher $voucher
     *
     * @return $this
     */
    public function addDesignatedVoucher($voucher)
    {
        $this->designatedVouchers->add($voucher);

        return $this;
    }

    /**
     * @param Voucher $voucher
     *
     * @return $this
     */
    public function removeDesignatedVoucher($voucher)
    {
        $this->designatedVouchers->removeElement($voucher);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setEmail($email)
    {
        parent::setEmail($email);

        $this->setUsername($email);
    }

    /**
     * @inheritdoc
     */
    public function setEmailCanonical($email)
    {
        parent::setEmailCanonical($email);

        $this->setUsernameCanonical($email);
    }
}
