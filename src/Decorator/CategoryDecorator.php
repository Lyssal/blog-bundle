<?php
/**
 * This file is part of a Lyssal project.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\BlogBundle\Decorator;

use Lyssal\BlogBundle\Entity\Category;
use Lyssal\Entity\Decorator\AbstractDecorator;

/**
 * @inheritDoc
 */
class CategoryDecorator extends AbstractDecorator
{
    /**
     * @inheritDoc
     */
    public function supports($entity)
    {
        return $entity instanceof Category;
    }
}
