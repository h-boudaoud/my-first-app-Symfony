<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 * @UniqueEntity(fields="reference", message="This reference is already used.")
 */
class Article implements \JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Regex(
     *     pattern="/^([a-zA-Z0-9\- _]+)$/m",
     *     htmlPattern = "^([a-zA-Z0-9\- _]+)$",
     *     match=true,
     *     message="The reference of category is not valid"
     * )
     */
    private $reference;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(
     *     pattern="/^([a-zA-Z0-9\- _]+)$/m",
     *     htmlPattern = "^([a-zA-Z0-9\- _]+)$",
     *     match=true,
     *     message="The name of category is not valid"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="decimal", precision=7, scale=2)
     * @Assert\PositiveOrZero
     */
    private $price = 0;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\LessThanOrEqual("today")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\GreaterThanOrEqual("createdAt")
     * @Assert\LessThanOrEqual("today")
     */

    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="integer")
     * @Assert\PositiveOrZero
     */
    private $stockQuantity = 0;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\PositiveOrZero
     */
    private $stockAlarm;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Notification", mappedBy="article", orphanRemoval=true)
     */
    private $notifications;


    public function __construct()
    {
        $this->notifications = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
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

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getStockQuantity(): ?int
    {
        return $this->stockQuantity;
    }

    public function setStockQuantity(int $stockQuantity): self
    {
        $this->stockQuantity = $stockQuantity;

        return $this;
    }

    public function getStockAlarm(): ?int
    {
        return $this->stockAlarm;
    }

    public function setStockAlarm(?int $stockAlarm): self
    {
        $this->stockAlarm = $stockAlarm;

        return $this;
    }


    /**
     * @return Collection|Notification[]
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setAuthor($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->contains($notification)) {
            $this->notifications->removeElement($notification);
            // set the owning side to null (unless already changed)
            if ($notification->getAuthor() === $this) {
                $notification->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        $notifications = null;
        foreach ($this->notifications as $item){
            $notifications[] = $item->jsonSerialize();
        }
        return [
            'id'=>$this->id,
            'reference'=>$this->reference,
            'name'=>$this->name,
            'price'=>$this->price,
            'description'=>$this->description,
            'createdAt'=>$this->createdAt,
            'updatedAt'=>$this->updatedAt,
            'stockQuantity'=>$this->stockQuantity,
            'stockAlarm'=>$this->stockAlarm,
            'category'=>['id'=>$this->category->getId(),'name'=>$this->category->getName()],
            'nb_notifications'=>Count($this->notifications->toArray()),
            'notifications' => $notifications
        ];
    }
}
