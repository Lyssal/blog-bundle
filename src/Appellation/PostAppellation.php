<?php
/**
 * This file is part of a Lyssal project.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\BlogBundle\Appellation;

use Lyssal\BlogBundle\Decorator\PostDecorator;
use Lyssal\BlogBundle\Entity\Post;
use Lyssal\EntityBundle\Appellation\AbstractDefaultAppellation;

/**
 * @inheritDoc
 */
class PostAppellation extends AbstractDefaultAppellation
{
    /**
     * @inheritDoc
     */
    public function supports($object)
    {
        return ($object instanceof Post || $object instanceof PostDecorator);
    }
}
