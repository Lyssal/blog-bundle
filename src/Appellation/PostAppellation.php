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
use Symfony\Component\Routing\RouterInterface;

/**
 * @inheritDoc
 */
class PostAppellation extends AbstractDefaultAppellation
{
    /**
     * @var \Symfony\Component\Routing\RouterInterface The router
     */
    protected $router;


    /**
     * {@inheritDoc}
     *
     * @param \Symfony\Component\Routing\RouterInterface $router The router
     */
    public function __construct(RouterInterface $router)
    {
        parent::__construct();

        $this->router = $router;
    }


    /**
     * @inheritDoc
     */
    public function supports($object)
    {
        return ($object instanceof Post || $object instanceof PostDecorator);
    }


    /**
     * {@inheritDoc}
     */
    public function appellationHtml($object)
    {
        return '<a href="'.$this->router->generate('lyssal_blog_post_show', ['post' => $object->getId()]).'">'.$this->appellation($object).'</a>';
    }
}
