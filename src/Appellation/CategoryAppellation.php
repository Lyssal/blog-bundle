<?php
/**
 * This file is part of a Lyssal project.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\BlogBundle\Appellation;

use Lyssal\BlogBundle\Decorator\CategoryDecorator;
use Lyssal\BlogBundle\Entity\Category;
use Lyssal\EntityBundle\Appellation\AbstractDefaultAppellation;

/**
 * @inheritDoc
 */
class CategoryAppellation extends AbstractDefaultAppellation
{
    /**
     * @inheritDoc
     */
    public function supports($object)
    {
        return ($object instanceof Category || $object instanceof CategoryDecorator);
    }
}
