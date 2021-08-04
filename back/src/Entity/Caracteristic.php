<?php

namespace App\Entity;

use App\Repository\CaracteristicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CaracteristicRepository::class)
 */
class Caracteristic
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
    private $name;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity=Product::class, mappedBy="caracteristic")
     */
    private $products;

    /**
     * @ORM\OneToMany(targetEntity=CaracteristicDetail::class, mappedBy="caracteristic")
     */
    private $caracteristicDetail;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->caracteristicDetail = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->addCaracteristic($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            $product->removeCaracteristic($this);
        }

        return $this;
    }

    /**
     * @return Collection|CaracteristicDetail[]
     */
    public function getCaracteristicDetail(): Collection
    {
        return $this->caracteristicDetail;
    }

    public function addCaracteristicDetail(CaracteristicDetail $caracteristicDetail): self
    {
        if (!$this->caracteristicDetail->contains($caracteristicDetail)) {
            $this->caracteristicDetail[] = $caracteristicDetail;
            $caracteristicDetail->setCaracteristic($this);
        }

        return $this;
    }

    public function removeCaracteristicDetail(CaracteristicDetail $caracteristicDetail): self
    {
        if ($this->caracteristicDetail->removeElement($caracteristicDetail)) {
            // set the owning side to null (unless already changed)
            if ($caracteristicDetail->getCaracteristic() === $this) {
                $caracteristicDetail->setCaracteristic(null);
            }
        }

        return $this;
    }
}
