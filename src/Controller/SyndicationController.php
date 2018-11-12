<?php
/**
 * This file is part of a Lyssal project.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\BlogBundle\Controller;

use Lyssal\BlogBundle\Syndication\PostGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * The controller for RSS and Atom.
 *
 * @Route("/", name="lyssal_blog_syndication_")
 */
class SyndicationController extends AbstractController
{
    /**
     * The Atom feed route.
     *
     * @param \Lyssal\BlogBundle\Entity\Blog $blog The blog
     *
     * @Route("blog-{blog}.atom", name="atom", methods={"GET"}, requirements={"blog"="\d+"})
     */
    public function atom($blog)
    {
        $blog = $this->container->get('lyssal.blog.manager.blog')->findOneById($blog);
        $feed = $this->container->get(PostGenerator::class)->generateAtom($blog);

        return new Response($feed, Response::HTTP_OK, [
            'Content-Type' => 'application/atom+xml'
        ]);
    }

    /**
     * The RSS feed route.
     *
     * @param \Lyssal\BlogBundle\Entity\Blog $blog The blog
     *
     * @Route("blog-{blog}.rss", name="rss", methods={"GET"}, requirements={"blog"="\d+"})
     */
    public function rss($blog)
    {
        $blog = $this->container->get('lyssal.blog.manager.blog')->findOneById($blog);
        $feed = $this->container->get(PostGenerator::class)->generateRss($blog);

        return new Response($feed, Response::HTTP_OK, [
            'Content-Type' => 'application/rss+xml'
        ]);
    }
}
