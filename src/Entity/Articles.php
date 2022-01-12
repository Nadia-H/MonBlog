<?php

namespace App\Entity;

use App\Repository\ArticlesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticlesRepository::class)
 */
class Articles
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=author::class, inversedBy="list_articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author_id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     */
    private $published_date;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creation_date;

    /**
     * @ORM\Column(type="datetime")
     */
    private $last_modified;

    /**
     * @ORM\ManyToMany(targetEntity=PostTags::class, mappedBy="article_id")
     */
    private $aricle_tags;

    /**
     * @ORM\OneToMany(targetEntity=Comments::class, mappedBy="article_id", orphanRemoval=true)
     */
    private $article_comments;

    public function __construct()
    {
        $this->article_tags = new ArrayCollection();
        $this->article_comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthorId(): ?author
    {
        return $this->author_id;
    }

    public function setAuthorId(?author $author_id): self
    {
        $this->author_id = $author_id;

        return $this;
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

    public function getPublishedDate(): ?\DateTimeInterface
    {
        return $this->published_date;
    }

    public function setPublishedDate(\DateTimeInterface $published_date): self
    {
        $this->published_date = $published_date;

        return $this;
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

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creation_date;
    }

    public function setCreationDate(\DateTimeInterface $creation_date): self
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    public function getLastModified(): ?\DateTimeInterface
    {
        return $this->last_modified;
    }

    public function setLastModified(\DateTimeInterface $last_modified): self
    {
        $this->last_modified = $last_modified;

        return $this;
    }

    /**
     * @return Collection|ArticleTags[]
     */
    public function getAricleTags(): Collection
    {
        return $this->aricle_tags;
    }

    public function addArticleTag(ArticleTags $articleTag): self
    {
        if (!$this->article_tags->contains($articleTag)) {
            $this->article_tags[] = $articleTag;
            $articleTag->addArticleId($this);
        }

        return $this;
    }

    public function removeArticleTag(ArticleTags $articleTag): self
    {
        if ($this->article_tags->removeElement($articleTag)) {
            $articleTag->removeArticleId($this);
        }

        return $this;
    }

    /**
     * @return Collection|Comments[]
     */
    public function getArticleComments(): Collection
    {
        return $this->article_comments;
    }

    public function addArticleComment(Comments $articleComment): self
    {
        if (!$this->article_comments->contains($articleComment)) {
            $this->article_comments[] = $articleComment;
            $articleComment->setArticleId($this);
        }

        return $this;
    }

    public function removeArticleComment(Comments $articleComment): self
    {
        if ($this->article_comments->removeElement($articleComment)) {
            // set the owning side to null (unless already changed)
            if ($articleComment->getArticleId() === $this) {
                $articleComment->setArticleId(null);
            }
        }

        return $this;
    }
}
