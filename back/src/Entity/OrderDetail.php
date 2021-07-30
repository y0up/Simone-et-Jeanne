<?php

namespace App\Entity;

use App\Repository\OrderDetailRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderDetailRepository::class)
 */
class OrderDetail
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $total;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToOne(targetEntity=PaymentDetail::class, inversedBy="orderDetail", cascade={"persist", "remove"})
     */
    private $PaymentDetail;

    /**
     * @ORM\OneToMany(targetEntity=OrderItem::class, mappedBy="orderDetail")
     */
    private $orderItems;

    /**
     * @ORM\OneToMany(targetEntity=OrderAdress::class, mappedBy="orderDetail", orphanRemoval=true)
     */
    private $orderAdresses;

    /**
     * @ORM\Column(type="float")
     */
    private $shippingPrice;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
        $this->orderAdresses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

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

    public function getPaymentDetail(): ?PaymentDetail
    {
        return $this->PaymentDetail;
    }

    public function setPaymentDetail(?PaymentDetail $PaymentDetail): self
    {
        $this->PaymentDetail = $PaymentDetail;

        return $this;
    }

    /**
     * @return Collection|OrderItem[]
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function addOrderItem(OrderItem $orderItem): self
    {
        if (!$this->orderItems->contains($orderItem)) {
            $this->orderItems[] = $orderItem;
            $orderItem->setOrderDetail($this);
        }

        return $this;
    }

    public function removeOrderItem(OrderItem $orderItem): self
    {
        if ($this->orderItems->removeElement($orderItem)) {
            // set the owning side to null (unless already changed)
            if ($orderItem->getOrderDetail() === $this) {
                $orderItem->setOrderDetail(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|OrderAdress[]
     */
    public function getOrderAdresses(): Collection
    {
        return $this->orderAdresses;
    }

    public function addOrderAdress(OrderAdress $orderAdress): self
    {
        if (!$this->orderAdresses->contains($orderAdress)) {
            $this->orderAdresses[] = $orderAdress;
            $orderAdress->setOrderDetail($this);
        }

        return $this;
    }

    public function removeOrderAdress(OrderAdress $orderAdress): self
    {
        if ($this->orderAdresses->removeElement($orderAdress)) {
            // set the owning side to null (unless already changed)
            if ($orderAdress->getOrderDetail() === $this) {
                $orderAdress->setOrderDetail(null);
            }
        }

        return $this;
    }

    public function getShippingPrice(): ?string
    {
        return $this->shippingPrice;
    }

    public function setShippingPrice(string $shippingPrice): self
    {
        $this->shippingPrice = $shippingPrice;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
