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
use Symfony\Component\Routing\RouterInterface;

/**
 * @inheritDoc
 */
class BlogAppellation extends AbstractDefaultAppellation
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
        return ($object instanceof Blog || $object instanceof BlogDecorator);
    }


    /**
     * {@inheritDoc}
     */
    public function appellationHtml($object)
    {
        return '<a href="'.$this->router->generate('lyssal_blog_blog_show', ['post' => $object->getId()]).'">'.$this->appellation($object).'</a>';
    }
}
