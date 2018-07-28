<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\Table(name="voucher_type")
 * @Serializer\ExclusionPolicy("all")
 */
class VoucherType
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", name="name")
     * @Serializer\Expose()
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Voucher", mappedBy="type")
     */
    protected $vouchers;

    public function __construct()
    {
        $this->vouchers = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVouchers(): Collection
    {
        return $this->vouchers;
    }

    public function addVoucher(Voucher $voucher): self
    {
        $this->vouchers->add($voucher);

        return $this;
    }

    public function removeVoucher(Voucher $voucher): self
    {
        $this->vouchers->removeElement($voucher);

        return $this;
    }
}
