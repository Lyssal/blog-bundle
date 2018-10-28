<?php
/**
 * This file is part of a Lyssal project.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * The blog controller.
 *
 * @Route("/", name="lyssal_blog_blog_")
 */
class BlogController extends AbstractController
{
    /**
     * Show a blog.
     *
     * @Route("/{blog}", name="show", requirements={"blog"="\d+"}, methods={"GET"})
     */
    public function show($blog)
    {
        /**
         * @var \Lyssal\BlogBundle\Entity\Blog $blog
         */
        $blog = $this->container->get('lyssal.blog.manager.blog')->findOneById($blog);

        if (null === $blog) {
            throw $this->createNotFoundException();
        }

        $posts = $this->container->get('lyssal.blog.manager.post')->getPagerFantaByBlog($blog);

        return $this->render('@LyssalBlog/blog/show.html.twig', [
            'page' => $blog->getPage(),
            'blog' => $this->container->get('lyssal.decorator')->get($blog),
            'posts' => $posts
        ]);
    }
}
