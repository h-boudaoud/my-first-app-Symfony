<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity as UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 * @UniqueEntity("name", entityClass="App\Entity\Category" ,message="This category is already used.")
 */
class Category  implements \JsonSerializable, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Article", mappedBy="category", orphanRemoval=true)
     */
    private $articles;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
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

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setCategory($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getCategory() === $this) {
                $article->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->name,
            serialize($this->articles)
                ]);
    }

    /**
     * @inheritDoc
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->name,
            $this->articles
        ) = unserialize($serialized);
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        $articles = null;
        foreach ($this->articles as $item){
            $articles[] = $item->jsonSerialize();
        }
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'nb_Articles'=>Count($this->articles->toArray()),
            'articles' => $articles
        ];
    }
}
