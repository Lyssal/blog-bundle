<?php
/**
 * This file is part of a Lyssal project.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Lyssal\BlogBundle\Controller\CategoryController;
use Lyssal\EntityBundle\Entity\ControllerableInterface;
use Lyssal\SeoBundle\Entity\Page;
use Lyssal\SeoBundle\Entity\PageableInterface;
use Lyssal\SeoBundle\Entity\Traits\PageTrait;

/**
 * A category.
 *
 * @ORM\MappedSuperclass(repositoryClass="Lyssal\BlogBundle\Repository\CategoryRepository")
 */
class Category implements PageableInterface, ControllerableInterface
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
     * @var \Lyssal\SeoBundle\Entity\Page The SEO page
     *
     * @ORM\ManyToOne(targetEntity="Lyssal\SeoBundle\Entity\Page", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $page;

    /**
     * @var \Lyssal\BlogBundle\Entity\Category The parent category
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $parent;

    /**
     * @var \Doctrine\Common\Collections\Collection<\Lyssal\BlogBundle\Entity\Category> The categories
     *
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     */
    protected $children;

    /**
     * @var \Doctrine\Common\Collections\Collection<\Lyssal\BlogBundle\Entity\Post> The posts
     *
     * @ORM\ManyToMany(targetEntity="Post", mappedBy="categories")
     */
    protected $posts;


    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->posts = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(Category $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(Category $child): self
    {
        if ($this->children->contains($child)) {
            $this->children->removeElement($child);
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->addCategory($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            $post->removeCategory($this);
        }

        return $this;
    }


    /**
     * Get the name.
     *
     * @return string The name
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
        return 'Categories/'.$this->page->getTitle();
    }

    /**
     * \Lyssal\EntityBundle\Entity\ControllerableInterface::getControllerProperties()
     */
    public function getControllerProperties(): array
    {
        return [CategoryController::class.'::show', ['category' => $this->id]];
    }
}
