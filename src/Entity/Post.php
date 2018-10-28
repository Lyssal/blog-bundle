<?php
/**
 * This file is part of a Lyssal project.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\BlogBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Lyssal\BlogBundle\Controller\PostController;
use Lyssal\EntityBundle\Entity\ControllerableInterface;
use Lyssal\SeoBundle\Entity\PageableInterface;
use Lyssal\SeoBundle\Entity\Traits\PageTrait;

/**
 * A post.
 *
 * @ORM\MappedSuperclass(repositoryClass="Lyssal\BlogBundle\Repository\PostRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Post implements PageableInterface, ControllerableInterface
{
    use PageTrait;


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
     * @ORM\JoinColumn(nullable=false)
     */
    protected $blog;

    /**
     * @var \Lyssal\SeoBundle\Entity\Page The SEO page
     *
     * @ORM\ManyToOne(targetEntity="Lyssal\SeoBundle\Entity\Page", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $page;

    /**
     * @var string The body
     *
     * @ORM\Column(type="text", nullable=false)
     */
    protected $body;

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
        $this->publishedFrom = new DateTime();
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

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

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
     * Init the creation date.
     *
     * @ORM\PrePersist()
     */
    public function initCreatedAt()
    {
        $this->createdAt = new DateTime();
    }

    /**
     * Init the last modification date.
     *
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function initUpdatedAt()
    {
        $this->updatedAt = new DateTime();
    }


    /**
     * Get the title.
     *
     * @return string The title
     */
    public function __toString()
    {
        return (string) $this->page;
    }


    /**
     * @see \Lyssal\Seo\Model\PageableInterface::getPatternForSlug()
     */
    public function getPatternForSlug()
    {
        $slugPattern = '';

        if (count($this->categories) > 0) {
            foreach ($this->categories as $category) {
                $slugPattern .= $category.'-';
            }

            $slugPattern = substr($slugPattern, 0, -1).'/';
        }

        $slugPattern .= $this->page->getTitle();

        return $slugPattern;
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
     * @return bool If it is accessible
     */
    public function isAccessible()
    {
        $now = new DateTime();

        return
            $this->page->isAccessible()
            && $this->blog->isAccessible()
            && $this->publishedFrom < $now
            && (null === $this->publishedUntil || $this->publishedUntil > $now)
        ;
    }

    /**
     * Get the accessible categories.
     *
     * @return \Lyssal\BlogBundle\Entity\Category[] The categories
     */
    public function getAccessibleCategories()
    {
        $categories = [];

        /**
         * @var \Lyssal\BlogBundle\Entity\Category $category
         */
        foreach ($this->categories as $category) {
            if ($category->isAccessible()) {
                $categories[] = $category;
            }
        }

        return $categories;
    }


    /**
     * \Lyssal\EntityBundle\Entity\ControllerableInterface::getControllerProperties()
     */
    public function getControllerProperties(): array
    {
        return [PostController::class.'::show', ['post' => $this->id]];
    }
}
