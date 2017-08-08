<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Shop
 * @author: Enache Ioan Ovidiu <i.ovidiuenache@yahoo.com>
 *
 * @ORM\Table(name="shops")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ShopRepository")
 */
class Shop
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
     * @ORM\Column(type="string", length=50)
     * @Assert\Length(
     *     max = 50,
     *     maxMessage = "name.too.long"
     * )
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     * @Assert\Length(
     *     max = 50,
     *     maxMessage = "name.too.long"
     * )
     */
    protected $address;

    /**
     * Bidirectional - One-To-Many (INVERSE SIDE)
     * One Shop can be the creation place for many vouchers
     *
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Voucher", mappedBy="shopWhereCreated")
     */
    protected $createdVouchers;

    /**
     * Bidirectional - One-To-Many (INVERSE SIDE)
     * One Shop can have assigned many users
     *
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="User", mappedBy="shop")
     */
    protected $assignedUsers;

    public function __construct()
    {
        $this->createdVouchers = new ArrayCollection();
        $this->assignedUsers = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->name;
    }

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getName() : ?string
    {
        return $this->name;
    }

    public function setName(string $name) : self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress() : ?string
    {
        return $this->address;
    }

    public function setAddress(string $address) : self
    {
        $this->address = $address;

        return $this;
    }

    public function getCreatedVouchers() : Collection
    {
        return $this->createdVouchers;
    }

    public function addCreatedVoucher(Voucher $voucher) : self
    {
        $this->createdVouchers->add($voucher);

        return $this;
    }

    public function removeCreatedVoucher(Voucher $voucher) : self
    {
        $this->createdVouchers->removeElement($voucher);

        return $this;
    }

    public function getAssignedUsers() : Collection
    {
        return $this->assignedUsers;
    }

    public function addAssignedUser(User $user) : self
    {
        $this->assignedUsers->add($user);

        return $this;
    }

    public function removeAssignedUser(User $user) : self
    {
        $this->assignedUsers->removeElement($user);

        return $this;
    }
}
