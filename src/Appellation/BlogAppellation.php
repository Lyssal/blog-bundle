<?php
/**
 * This file is part of a Lyssal project.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\BlogBundle\Appellation;

use Lyssal\BlogBundle\Decorator\BlogDecorator;
use Lyssal\BlogBundle\Entity\Blog;
use Lyssal\EntityBundle\Appellation\AbstractDefaultAppellation;

/**
 * @inheritDoc
 */
class BlogAppellation extends AbstractDefaultAppellation
{
    /**
     * @inheritDoc
     */
    public function supports($object)
    {
        return ($object instanceof Blog || $object instanceof BlogDecorator);
    }
}
