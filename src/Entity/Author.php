<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AuthorRepository::class)
 */
class Author
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $first_name;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $last_name;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $password;

    /**
     * @ORM\Column(type="datetime")
     */
    private $registered_date;

    /**
     * @ORM\Column(type="datetime")
     */
    private $last_login;

    /**
     * @ORM\Column(type="text")
     */
    private $intro;

    /**
     * @ORM\OneToMany(targetEntity=Articles::class, mappedBy="author_id", orphanRemoval=true)
     */
    private $list_articles;

    public function __construct()
    {
        $this->list_articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

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

    public function getRegisteredDate(): ?\DateTimeInterface
    {
        return $this->registered_date;
    }

    public function setRegisteredDate(\DateTimeInterface $registered_date): self
    {
        $this->registered_date = $registered_date;

        return $this;
    }

    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->last_login;
    }

    public function setLastLogin(\DateTimeInterface $last_login): self
    {
        $this->last_login = $last_login;

        return $this;
    }

    public function getIntro(): ?string
    {
        return $this->intro;
    }

    public function setIntro(string $intro): self
    {
        $this->intro = $intro;

        return $this;
    }

    /**
     * @return Collection|Articles[]
     */
    public function getListArticles(): Collection
    {
        return $this->list_articles;
    }

    public function addListArticle(Articles $listArticle): self
    {
        if (!$this->list_articles->contains($listArticle)) {
            $this->list_articles[] = $listArticle;
            $listArticle->setAuthorId($this);
        }

        return $this;
    }

    public function removeListArticle(Articles $listArticle): self
    {
        if ($this->list_articles->removeElement($listArticle)) {
            // set the owning side to null (unless already changed)
            if ($listArticle->getAuthorId() === $this) {
                $listArticle->setAuthorId(null);
            }
        }

        return $this;
    }
}
