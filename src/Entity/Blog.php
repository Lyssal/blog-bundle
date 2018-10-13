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

/**
 * A blog.
 *
 * @ORM\MappedSuperclass(repositoryClass="Lyssal\BlogBundle\Repository\BlogRepository")
 */
class Blog
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
     * @var string The name
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $name;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
        return (string) $this->name;
    }
}
