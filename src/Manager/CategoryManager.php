<?php
/**
 * This file is part of a Lyssal project.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\BlogBundle\Manager;

use Lyssal\BlogBundle\Entity\Blog;
use Lyssal\BlogBundle\Entity\Category;
use Lyssal\Doctrine\Orm\Manager\EntityManager;
use Lyssal\Doctrine\Orm\QueryBuilder;

/**
 * @inheritDoc
 */
class CategoryManager extends EntityManager
{
    /**
     * Get the categories by parent.
     *
     * @param \Lyssal\BlogBundle\Entity\Blog|null     $blog           The blog
     * @param \Lyssal\BlogBundle\Entity\Category|null $categoryParent The parent category
     *
     * @return \Lyssal\BlogBundle\Entity\Category[] The sub categories
     */
    public function getByParent(?Blog $blog = null, ?Category $categoryParent = null): array
    {
        $conditions = [];

        if (null !== $blog) {
            $conditions['blog'] = $blog;
        }

        if (null === $categoryParent) {
            $conditions[QueryBuilder::WHERE_NULL] = 'parent';
        } else {
            $conditions['parent'] = $categoryParent;
        }

        return $this->findBy($conditions, ['position' => 'ASC']);
    }
}
