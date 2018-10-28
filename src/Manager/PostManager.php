<?php
/**
 * This file is part of a Lyssal project.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\BlogBundle\Manager;

use Doctrine\Common\Collections\Criteria;
use Lyssal\BlogBundle\Entity\Blog;
use Lyssal\BlogBundle\Entity\Category;
use Lyssal\Doctrine\Orm\Manager\EntityManager;
use Lyssal\Doctrine\Orm\QueryBuilder;
use Pagerfanta\Pagerfanta;

/**
 * @inheritDoc
 */
class PostManager extends EntityManager
{
    /**
     * Get the PagerFanta by blog.
     *
     * @param \Lyssal\BlogBundle\Entity\Blog $blog        The blog
     * @param int                            $limit       The limit
     * @param int                            $currentPage The current page
     *
     * @return \Pagerfanta\Pagerfanta The Pagerfanta
     */
    public function getPagerFantaByBlog(Blog $blog, $limit = 20, $currentPage = 1): Pagerfanta
    {
        return $this->getRepository()->getPagerFantaFindBy(
            ['blog' => $blog],
            ['publishedFrom' => Criteria::DESC],
            $limit,
            $currentPage
         );
    }

    /**
     * Get the PagerFanta by category.
     *
     * @param \Lyssal\BlogBundle\Entity\Category $category    The category
     * @param int                                $limit       The limit
     * @param int                                $currentPage The current page
     *
     * @return \Pagerfanta\Pagerfanta The Pagerfanta
     */
    public function getPagerFantaByCategory(Category $category, $limit = 20, $currentPage = 1): Pagerfanta
    {
        return $this->getRepository()->getPagerFantaFindBy(
            ['category' => $category],
            ['publishedFrom' => Criteria::DESC],
            $limit,
            $currentPage,
            [
                QueryBuilder::INNER_JOINS => ['categories' => 'category']
            ]
         );
    }
}
