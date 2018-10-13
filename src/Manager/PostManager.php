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
use Lyssal\Doctrine\Orm\Manager\EntityManager;

/**
 * @inheritDoc
 */
class PostManager extends EntityManager
{
    public function getPagerFanta(Blog $blog, $limit = 20, $currentPage = 1)
    {
        return $this->getRepository()->getPagerFantaFindBy(
            ['blog' => $blog],
            ['publishedFrom' => Criteria::DESC],
            $limit,
            $currentPage
         );
    }
}
