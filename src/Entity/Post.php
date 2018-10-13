<?php
/**
 * This file is part of a Lyssal project.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\BlogBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * A post.
 *
 * @ORM\MappedSuperclass(repositoryClass="Lyssal\BlogBundle\Repository\PostRepository")
 */
class Post
{
    /**
     * @var int The ID
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var \Lyssal\BlogBundle\Entity\Blog The blog
     *
     * @ORM\ManyToOne(targetEntity="Blog", inversedBy="posts")
     */
    protected $blog;

    /**
     * @var string The title
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $title;

    /**
     * @var string The body
     *
     * @ORM\Column(type="text", nullable=false)
     */
    protected $body;

    /**
     * @var bool If the post is online
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $online;

    /**
     * @var \DateTime The creation date
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @var \DateTime The last modification date
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $updatedAt;

    /**
     * @var \DateTime The publication start date
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $publishedFrom;

    /**
     * @var \DateTime The publication end date
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $publishedUntil;

    /**
     * @var \Doctrine\Common\Collections\Collection<\Lyssal\BlogBundle\Entity\Category> The categories
     *
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="posts")
     */
    protected $categories;


    public function __construct()
    {
        $this->online = true;
        $this->categories = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBlog(): ?Blog
    {
        return $this->blog;
    }

    public function setBlog(?Blog $blog): self
    {
        $this->blog = $blog;

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

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getOnline(): ?bool
    {
        return $this->online;
    }

    public function setOnline(bool $online): self
    {
        $this->online = $online;

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

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getPublishedFrom(): \DateTimeInterface
    {
        return $this->publishedFrom;
    }

    public function setPublishedFrom(\DateTimeInterface $publishedFrom): self
    {
        $this->publishedFrom = $publishedFrom;

        return $this;
    }

    public function getPublishedUntil(): ?\DateTimeInterface
    {
        return $this->publishedUntil;
    }

    public function setPublishedUntil(?\DateTimeInterface $publishedUntil): self
    {
        $this->publishedUntil = $publishedUntil;

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
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

        return $this;
    }


    /**
     * Get the title.
     *
     * @return string The title
     */
    public function __toString()
    {
        return (string) $this->title;
    }


    /**
     * Get the displayed date.
     *
     * @return \DateTime The published date
     */
    public function getDate()
    {
        return $this->publishedFrom;
    }

    /**
     * Return if the post is visible in front.
     *
     * @return bool If it is visible
     */
    public function isVisible()
    {
        $now = new DateTime();

        return
            $this->online
            && $this->publishedFrom < $now
            && (null === $this->publishedUntil || $this->publishedUntil > $now)
        ;
    }
}
