<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AvailableService
 * @author: Enache Ioan Ovidiu <i.ovidiuenache@yahoo.com>
 *
 * @ORM\Table(name="available_services")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AvailableServiceRepository")
 */
class AvailableService
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
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $price;

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

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price) : self
    {
        $this->price = $price;

        return $this;
    }
}
