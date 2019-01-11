<?php
/**
 * This file is part of a Lyssal project.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\BlogBundle\Manager;

use DateTime;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Lyssal\BlogBundle\Entity\Blog;
use Lyssal\BlogBundle\Entity\Category;
use Lyssal\BlogBundle\Entity\Post;
use Lyssal\Doctrine\Orm\Manager\EntityManager;
use Lyssal\Doctrine\Orm\QueryBuilder;
use Pagerfanta\Pagerfanta;

/**
 * @inheritDoc
 */
class PostManager extends EntityManager
{
    /**
     * @var array The default conditions
     */
    private static $DEFAULT_CONDITIONS = [
        'page.online' => true
    ];

    /**
     * @var array The default sort
     */
    private static $DEFAULT_ORDERBY = [
      'publishedFrom' => Criteria::DESC
    ];


    /**
     * @inheritDoc
     */
    public function __construct(EntityManagerInterface $entityManager, $class)
    {
        parent::__construct($entityManager, $class);

        self::$DEFAULT_CONDITIONS = array_merge(self::$DEFAULT_CONDITIONS, [
            QueryBuilder::WHERE_LESS_OR_EQUAL => ['publishedFrom' => new DateTime()],
            QueryBuilder::OR_WHERE => [
                QueryBuilder::WHERE_NULL => 'publishedUntil',
                QueryBuilder::WHERE_GREATER => ['publishedUntil' => new DateTime()]
            ]
        ]);
    }


    /**
     * Get the PagerFanta by blog.
     *
     * @param \Lyssal\BlogBundle\Entity\Blog $blog        The blog
     * @param int                            $limit       The limit
     * @param int                            $currentPage The current page
     *
     * @return \Pagerfanta\Pagerfanta The Pagerfanta
     */
    public function getPagerFantaByBlog(Blog $blog, $limit = Post::POSTS_PER_PAGE, $currentPage = 1): Pagerfanta
    {
        $conditions = array_merge(self::$DEFAULT_CONDITIONS, [
            'blog' => $blog
        ]);

        return $this->getRepository()->getPagerFantaFindBy(
            $conditions,
            self::$DEFAULT_ORDERBY,
            $limit,
            $currentPage,
            [
                QueryBuilder::INNER_JOINS => [
                    'page' => 'page'
                ]
            ]
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
    public function getPagerFantaByCategory(Category $category, $limit = Post::POSTS_PER_PAGE, $currentPage = 1): Pagerfanta
    {
        $conditions = array_merge(self::$DEFAULT_CONDITIONS, [
            'category' => $category
        ]);

        return $this->getRepository()->getPagerFantaFindBy(
            $conditions,
            self::$DEFAULT_ORDERBY,
            $limit,
            $currentPage,
            [
                QueryBuilder::INNER_JOINS => [
                    'categories' => 'category',
                    'page' => 'page'
                ]
            ]
         );
    }
}
