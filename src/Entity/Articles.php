<?php

namespace App\Entity;

use App\Repository\ArticlesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticlesRepository::class)
 */
class Articles //class article qui représente la table articles dans la base de donnée 'monblog'
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
    private $author_id; //l'auteur de l'article, cet attribut relie la table articles à la table author.
    // Un auteur peut rédiger plusieurs articles mais un articles n'a qu'un seul auteur

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $title; //le titre de l'article

    /**
     * @ORM\Column(type="datetime")
     */
    private $published_date; //la date de publication de l'article

    /**
     * @ORM\Column(type="text")
     */
    private $content; //le contenu de l'article

    /**
     * @ORM\Column(type="datetime")
     */
    private $creation_date; //la date de création de l'article. Cette date représente
    // la date à laquelle un auteur a crée l'article et commencé à le rédigé

    /**
     * @ORM\Column(type="datetime")
     */
    private $last_modified; //la dernière date de modification: que un article déjà publié ou encore en rédaction

    /**
     * @ORM\ManyToMany(targetEntity=ArticleTags::class, mappedBy="article_id")
     */
    private $article_tags; //les tags associés à l'article. Cet attribut relie la table ArticlesTags et
    // la table articles. Un article peut être lié à plusieurs éléments de la table articlesTags et vice-versa

    /**
     * @ORM\OneToMany(targetEntity=Comments::class, mappedBy="article_id", orphanRemoval=true)
     */
    private $article_comments; //les commentaires relatif à un article. Cet attribu relie la table Articles
    // et la table comments

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
