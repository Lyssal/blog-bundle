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
 * The category controller.
 *
 * @Route("/", name="lyssal_blog_category_")
 */
class CategoryController extends AbstractController
{
    /**
     * Show a category.
     *
     * @Route("/category/{category}", name="show", requirements={"category"="\d+"}, methods={"GET"})
     */
    public function show($category)
    {
        /**
         * @var \Lyssal\BlogBundle\Entity\Category $category
         */
        $category = $this->container->get('lyssal.blog.manager.category')->findOneById($category);

        if (null === $category || !$category->isAccessible()) {
            throw $this->createNotFoundException();
        }

        $posts = $this->container->get('lyssal.blog.manager.post')->getPagerFantaByCategory($category);

        return $this->render('@LyssalBlog/category/show.html.twig', [
            'page' => $category->getPage(),
            'category' => $this->container->get('lyssal.decorator')->get($category),
            'posts' => $posts
        ]);
    }
}
