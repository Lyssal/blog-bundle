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
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Lyssal\BlogBundle\Controller\BlogController;
use Lyssal\EntityBundle\Entity\ControllerableInterface;
use Lyssal\SeoBundle\Entity\PageableInterface;
use Lyssal\SeoBundle\Entity\Traits\PageTrait;

/**
 * A blog.
 *
 * @ORM\MappedSuperclass(repositoryClass="Lyssal\BlogBundle\Repository\BlogRepository")
 */
class Blog implements PageableInterface, ControllerableInterface
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
     * @var \Doctrine\Common\Collections\Collection<\Lyssal\BlogBundle\Entity\Category> The categories
     *
     * @ORM\OneToMany(targetEntity="Category", mappedBy="blog")
     * @ORM\OrderBy({"position"="ASC"})
     */
    protected $categories;

    /**
     * @var \Doctrine\Common\Collections\Collection<\Lyssal\BlogBundle\Entity\Post> The posts
     *
     * @ORM\OneToMany(targetEntity="Post", mappedBy="blog")
     */
    protected $posts;


    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->posts = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
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
            $category->setBlog($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
            // set the owning side to null (unless already changed)
            if ($category->getBlog() === $this) {
                $category->setBlog(null);
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
            $post->setBlog($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            // set the owning side to null (unless already changed)
            if ($post->getBlog() === $this) {
                $post->setBlog(null);
            }
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
     * \Lyssal\EntityBundle\Entity\ControllerableInterface::getControllerProperties()
     */
    public function getControllerProperties(): array
    {
        return [BlogController::class.'::show', ['blog' => $this->id]];
    }


    /**
     * Return if the blog is visible in front.
     *
     * @return bool If it is accessible
     */
    public function isAccessible()
    {
        return
            null !== $this->page
            && $this->page->isAccessible()
        ;
    }

    /**
     * Get the category parents.
     *
     * @return \Lyssal\BlogBundle\Entity\Category[] The parents
     */
    public function getCategoryParents()
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->isNull('parent'))
            ->orderBy(['position' => 'ASC']);

        return $this->categories->matching($criteria);
    }
}
