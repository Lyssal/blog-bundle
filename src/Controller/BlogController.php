<?php
/**
 * This file is part of a Lyssal project.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\BlogBundle\Controller;

use Lyssal\BlogBundle\Entity\Post;
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
     * @Route("/{blog}", name="show", requirements={"blog"="\d+", "page"="\d+"}, methods={"GET"})
     */
    public function show($blog, $page = 1)
    {
        /**
         * @var \Lyssal\BlogBundle\Entity\Blog $blog
         */
        $blog = $this->container->get('lyssal.blog.manager.blog')->findOneById($blog);

        if (null === $blog || !$blog->isAccessible()) {
            throw $this->createNotFoundException();
        }

        $posts = $this->container->get('lyssal.blog.manager.post')
             ->getPagerFantaByBlog($blog, Post::POSTS_PER_PAGE, $page);

        return $this->render('@LyssalBlog/blog/show.html.twig', [
            'page' => $blog->getPage(),
            'blog' => $this->container->get('lyssal.decorator')->get($blog),
            'posts' => $posts
        ]);
    }
}
