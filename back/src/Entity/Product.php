<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
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
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $SKU;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=Review::class, mappedBy="product", orphanRemoval=true)
     */
    private $reviews;

    /**
     * @ORM\ManyToMany(targetEntity=Caracteristic::class, inversedBy="products")
     */
    private $caracteristic;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="products")
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity=OrderItem::class, mappedBy="product", orphanRemoval=true)
     */
    private $orderItems;

    /**
     * @ORM\OneToMany(targetEntity=CartItem::class, mappedBy="product", orphanRemoval=true)
     */
    private $cartItems;

    /**
     * @ORM\Column(type="boolean")
     */
    private $new;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="favorite")
     */
    private $users;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $brand;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="product", cascade={"persist"}, orphanRemoval=true)
     */
    private $images;

    /**
     * @ORM\ManyToMany(targetEntity=CaracteristicDetail::class, inversedBy="products")
     */
    private $caracteristicDetails;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantity;

    /**
     * @ORM\Column(type="integer")
     */
    private $weight;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
        $this->caracteristic = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->cartItems = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->caracteristicDetails = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSKU(): ?string
    {
        return $this->SKU;
    }

    public function setSKU(?string $SKU): self
    {
        $this->SKU = $SKU;

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
     * @return Collection|Review[]
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setProduct($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getProduct() === $this) {
                $review->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Caracteristic[]
     */
    public function getCaracteristic(): Collection
    {
        return $this->caracteristic;
    }

    public function addCaracteristic(Caracteristic $caracteristic): self
    {
        if (!$this->caracteristic->contains($caracteristic)) {
            $this->caracteristic[] = $caracteristic;
        }

        return $this;
    }

    public function removeCaracteristic(Caracteristic $caracteristic): self
    {
        $this->caracteristic->removeElement($caracteristic);

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    public function getOrderItems(): ?OrderItem
    {
        return $this->orderItems;
    }

    public function setOrderItems(?OrderItem $orderItem): self
    {
        // unset the owning side of the relation if necessary
        if ($orderItem === null && $this->orderItems !== null) {
            $this->orderItems->setProduct(null);
        }

        // set the owning side of the relation if necessary
        if ($orderItem !== null && $orderItem->getProduct() !== $this) {
            $orderItem->setProduct($this);
        }

        $this->orderItems = $orderItem;

        return $this;
    }

    /**
     * @return Collection|CartItem[]
     */
    public function getCartItems(): Collection
    {
        return $this->cartItems;
    }

    public function addCartItem(CartItem $cartItem): self
    {
        if (!$this->cartItems->contains($cartItem)) {
            $this->cartItems[] = $cartItem;
            $cartItem->setProduct($this);
        }

        return $this;
    }

    public function removeCartItem(CartItem $cartItem): self
    {
        if ($this->cartItems->removeElement($cartItem)) {
            // set the owning side to null (unless already changed)
            if ($cartItem->getProduct() === $this) {
                $cartItem->setProduct(null);
            }
        }

        return $this;
    }

    public function getNew(): ?bool
    {
        return $this->new;
    }

    public function setNew(bool $new): self
    {
        $this->new = $new;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addFavorite($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeFavorite($this);
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(?string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * did a user added this product to favorite
     *
     * @param User $user
     * @return boolean
     */
    public function likedByUser(User $user) : bool {
        foreach ($this->getUsers() as $a) {
            if ($a === $user ) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setProduct($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getProduct() === $this) {
                $image->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CaracteristicDetail[]
     */
    public function getCaracteristicDetails(): Collection
    {
        return $this->caracteristicDetails;
    }

    public function addCaracteristicDetail(CaracteristicDetail $caracteristicDetail): self
    {
        if (!$this->caracteristicDetails->contains($caracteristicDetail)) {
            $this->caracteristicDetails[] = $caracteristicDetail;
        }

        return $this;
    }

    public function removeCaracteristicDetail(CaracteristicDetail $caracteristicDetail): self
    {
        $this->caracteristicDetails->removeElement($caracteristicDetail);

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }
}
