<?php
/**
 * This file is part of a Lyssal project.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\BlogBundle\Repository;

use Lyssal\Doctrine\Orm\Repository\EntityRepository;
use Lyssal\Pagerfanta\Repository\Traits\DoctrineOrmTrait;

/**
 * @inheritDoc
 */
class PostRepository extends EntityRepository
{
    use DoctrineOrmTrait;
}
