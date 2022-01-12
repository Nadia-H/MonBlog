<?php

namespace App\Entity;

use App\Repository\PostTagsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PostTagsRepository::class)
 */
class ArticleTags
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Articles::class, inversedBy="aricle_tags")
     */
    private $article_id;

    /**
     * @ORM\OneToOne(targetEntity=Tags::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $tag_id;

    public function __construct()
    {
        $this->article_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Articles[]
     */
    public function getArticleId(): Collection
    {
        return $this->article_id;
    }

    public function addArticleId(Articles $articleId): self
    {
        if (!$this->article_id->contains($articleId)) {
            $this->article_id[] = $articleId;
        }

        return $this;
    }

    public function removeArticleId(Articles $articleId): self
    {
        $this->article_id->removeElement($articleId);

        return $this;
    }

    public function getTagId(): ?Tags
    {
        return $this->tag_id;
    }

    public function setTagId(Tags $tag_id): self
    {
        $this->tag_id = $tag_id;

        return $this;
    }
}
