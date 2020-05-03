<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="userName", message="This userName is already used.")
 * @UniqueEntity(fields="email", message="This email is already used.")
 */
class User  implements UserInterface
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
     *     pattern="/^([a-zA-Z0-9\- _\.]+)$/m",
     *     htmlPattern = "^([a-zA-Z0-9\- _\.]+)$",
     *     match=true,
     *     message="The userName is not valid"
     * )
     */
    private $userName;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Regex(
     *     pattern="/^[a-z0-9]([a-z0-9\- _\.]+)@([a-z0-9\- _\.]+)(\.[a-z]+)$/m",
     *     htmlPattern = "^[a-z0-9]([a-z0-9\- _\.]+)@([a-z0-9\- _\.]+)(\.[a-z]+)$",
     *     match=true,
     *     message="The email is not valid"
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(
     *     pattern="/(((?=[.\S]*\d)(?=[.\S]*[a-z])(?=[.\S]*[A-Z])(?=[.\S]*[<>&@$#%_~¤£!§\*\(\[\)\]\/\.\|\*\-\=])).{8,})/m",
     *     htmlPattern="/(((?=[.\S]*\d)(?=[.\S]*[a-z])(?=[.\S]*[A-Z])(?=[.\S]*[<>&@$#%_~¤£!§\*\(\[\)\]\/\.\|\*\-\=])).{8,})",
     *     match=true,
     *     message="The password is not valid, it must contain at least one lower case letter, a capital letter, a digital and a character: <, >,  &,  @, $, #, %, _, ~, ¤, £, !, §, *, (, [, ), ], /, ., |, *, -, ="
     * )
     */
    private $password;

    /**
     * @ORM\Column(type="json", nullable=true)
     * @Assert\Length(min=8, minMessage="", max="16", maxMessage="")
     */
    private $roles = ['ROLE_USER'];

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(
     *     pattern="/^([\w\-\. ]+)$/m",
     *     htmlPattern = "^([\w\-\. ]+)$",
     *     match=true,
     *     message="The firstName is not valid"
     * )
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(
     *     pattern="/^([\w\-\. ]+)$/m",
     *     htmlPattern = "^([\w\-\. ]+)$",
     *     match=true,
     *     message="The lastName is not valid"
     * )
     */
    private $lastName;

    /**
     * @ORM\Column(type="date")
     * @Assert\LessThanOrEqual("-18 years")
     */
    private $birthday;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Notification", mappedBy="author", orphanRemoval=true)
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

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(?array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
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
            $notification->setAthor($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->contains($notification)) {
            $this->notifications->removeElement($notification);
            // set the owning side to null (unless already changed)
            if ($notification->getAthor() === $this) {
                $notification->setAthor(null);
            }
        }

        return $this;
    }
}
