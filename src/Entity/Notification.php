<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NotificationRepository")
 * @UniqueEntity(fields={"article","user"}, message="A comment written by you is already posted for this article.")
 */
class Notification implements \JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="notifications")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Choice(
     *     choices = {0,1,2,3,4,5},
     *     message = "Choose a note between 1 and 5."
     * )
     */
    private $starRating;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Article")
     * @ORM\JoinColumn(nullable=false)
     */
    private $article;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getStarRating(): ?int
    {
        return $this->starRating;
    }

    public function setStarRating(?int $starRating): self
    {
        $this->starRating = $starRating;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }


    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'id'=>$this->id,
            'content'=>$this->content,
            'createdAt'=>$this->createdAt,
            'starRating'=>$this->starRating,
            'article'=>['id'=>$this->article->getId(),'name'=>$this->article->getName()],
            'author'=>['id'=>$this->author->getId(),'name'=>$this->author->getUserName()],
        ];
    }
}
