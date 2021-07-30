<?php

namespace App\Entity;

use App\Repository\ShippingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ShippingRepository::class)
 */
class Shipping
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\OneToMany(targetEntity=ShoppingSession::class, mappedBy="shipping")
     */
    private $shoppingSessions;

    public function __construct()
    {
        $this->shoppingSessions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection|ShoppingSession[]
     */
    public function getShoppingSessions(): Collection
    {
        return $this->shoppingSessions;
    }

    public function addShoppingSession(ShoppingSession $shoppingSession): self
    {
        if (!$this->shoppingSessions->contains($shoppingSession)) {
            $this->shoppingSessions[] = $shoppingSession;
            $shoppingSession->setShipping($this);
        }

        return $this;
    }

    public function removeShoppingSession(ShoppingSession $shoppingSession): self
    {
        if ($this->shoppingSessions->removeElement($shoppingSession)) {
            // set the owning side to null (unless already changed)
            if ($shoppingSession->getShipping() === $this) {
                $shoppingSession->setShipping(null);
            }
        }

        return $this;
    }
}
