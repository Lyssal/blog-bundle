<?php
/**
 * This file is part of a Lyssal project.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\BlogBundle\Decorator;

use Lyssal\BlogBundle\Entity\Blog;
use Lyssal\Entity\Decorator\AbstractDecorator;

/**
 * @inheritDoc
 */
class BlogDecorator extends AbstractDecorator
{
    /**
     * @inheritDoc
     */
    public function supports($entity)
    {
        return $entity instanceof Blog;
    }
}
