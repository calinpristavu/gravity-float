<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Voucher", mappedBy="shopWhereCreated")
     */
    protected $createdVouchers;

    public function __construct()
    {
        $this->createdVouchers = new ArrayCollection();
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
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     *
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
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
}
