<?php
/**
 * This file is part of a Lyssal project.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\BlogBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Lyssal\BlogBundle\Controller\BlogController;
use Lyssal\EntityBundle\Entity\ControllerableInterface;
use Lyssal\SeoBundle\Entity\Page;
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
     * @var \Doctrine\Common\Collections\Collection<\Lyssal\BlogBundle\Entity\Post> The posts
     *
     * @ORM\OneToMany(targetEntity="Post", mappedBy="blog")
     */
    protected $posts;


    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
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
}
