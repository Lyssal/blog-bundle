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
 * The post controller.
 *
 * @Route("/", name="lyssal_blog_post_")
 */
class PostController extends AbstractController
{
    /**
     * Show a post.
     *
     * @Route("/post/{post}", name="show", requirements={"post"="\d+"}, methods={"GET"})
     */
    public function show($post)
    {
        $post = $this->container->get('lyssal.blog.manager.post')->findOneById($post);

        if (null === $post) {
            throw $this->createNotFoundException();
        }

        return $this->render('@LyssalBlog/post/show.html.twig', [
            'post' => $this->container->get('lyssal.decorator')->get($post)
        ]);
    }
}
