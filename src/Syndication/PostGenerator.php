<?php
/**
 * This file is part of a Lyssal project.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\BlogBundle\Syndication;

use Lyssal\BlogBundle\Entity\Blog;
use Lyssal\BlogBundle\Manager\PostManager;
use Lyssal\Entity\Appellation\AppellationManager;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Zend\Feed\Writer\Feed;

/**
 * Generate the RSS for posts.
 */
class PostGenerator
{
    /**
     * @var int Number of posts by default
     */
    const DEFAULT_COUNT = 10;


    /**
     * @var \Symfony\Component\Routing\Generator\UrlGeneratorInterface The URL generator
     */
    protected $urlGenerator;

    /**
     * @var \Lyssal\Entity\Appellation\AppellationManager The appellation manager
     */
    protected $appellationManager;

    /**
     * @var \Lyssal\BlogBundle\Manager\PostManager The post manager
     */
    protected $postManager;


    /**
     * PostGenerator constructor.
     *
     * @param \Symfony\Component\Routing\Generator\UrlGeneratorInterface $urlGenerator       The URL generator
     * @param \Lyssal\Entity\Appellation\AppellationManager              $appellationManager The appellation manager
     * @param \Lyssal\BlogBundle\Manager\PostManager                     $postManager        The post manager
     */
    public function __construct(UrlGeneratorInterface $urlGenerator, AppellationManager $appellationManager, PostManager $postManager)
    {
        $this->urlGenerator = $urlGenerator;
        $this->appellationManager = $appellationManager;
        $this->postManager = $postManager;
    }


    /**
     * Generate the RSS.
     *
     * @param \Lyssal\BlogBundle\Entity\Blog $blog  The blog
     * @param int                            $count The item count
     *
     * @return string The feed content
     */
    public function generateRss(Blog $blog, $count = self::DEFAULT_COUNT)
    {
        $feed = $this->generate($blog, $count);

        return $feed->export('rss');
    }

    /**
     * Generate the Atom.
     *
     * @param \Lyssal\BlogBundle\Entity\Blog $blog  The blog
     * @param int                            $count The item count
     *
     * @return string The feed content
     */
    public function generateAtom(Blog $blog, $count = self::DEFAULT_COUNT)
    {
        $feed = $this->generate($blog, $count);

        return $feed->export('atom');
    }

    /**
     * Generate the feed.
     *
     * @param \Lyssal\BlogBundle\Entity\Blog $blog  The blog
     * @param int                            $count The item count
     *
     * @return \Zend\Feed\Writer\Feed The feed
     */
    protected function generate(Blog $blog, $count = self::DEFAULT_COUNT)
    {
        $page = $blog->getPage();
        $website = $page->getWebsite();
        $posts = $this->postManager->getPagerFantaByBlog($blog, $count);
        $feed = new Feed();

        $feed->setTitle($this->appellationManager->appellation($blog));
        $feed->setFeedLink($this->urlGenerator->generate('lyssal_blog_syndication_atom', ['blog' => $blog->getId()]), 'atom');
        $feed->setDescription($page->getDescription());
        $feed->setDateModified($page->getCreatedAt());
        if (null !== $website->getHomePage()) {
            $feed->setLink($this->urlGenerator->generate('lyssal_seo_page_show', ['slug' => $website->getHomePage()->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL));
        }
        if (null !== $website->getAuthor()) {
            $feed->addAuthor([
                'name' => $website->getAuthor()
            ]);
        }
        if (null !== $website->getCopyright()) {
            $feed->setCopyright($website->getCopyright());
        }

        /**
         * @var \Lyssal\BlogBundle\Entity\Post $post
         */
        foreach ($posts as $post) {
            $page = $post->getPage();
            $item = $feed->createEntry();

            $item->setTitle($this->appellationManager->appellation($post));
            $item->setLink($this->urlGenerator->generate('lyssal_seo_page_show', ['slug' => $page->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL));
            $item->setContent($post->getBody());
            $item->setDateCreated($post->getCreatedAt());
            $item->setDateModified($post->getDate());
            $item->setDescription($page->getDescription());

            $feed->addEntry($item);
        }

        return $feed;
    }
}
