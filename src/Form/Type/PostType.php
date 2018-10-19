<?php
/**
 * This file is part of a Lyssal project.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\BlogBundle\Form\Type;

use Lyssal\SeoBundle\Form\Type\PageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * The Post form.
 */
class PostType extends AbstractType
{
    /**
     * @var string The Post classname
     */
    protected $postClassname;


    /**
     * Constructor.
     *
     * @param string $postClassname The Post classname
     */
    public function __construct($postClassname)
    {
        $this->postClassname = $postClassname;
    }


    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('blog', null, [
                'label' => 'blog',
                'translation_domain' => 'LyssalBlogBundle'
            ])
            ->add('page', PageType::class, [
                'label' => 'page',
                'translation_domain' => 'LyssalSeoBundle'
            ])
            ->add('body', null, [
                'label' => 'body',
                'translation_domain' => 'LyssalBlogBundle'
            ])
            ->add('online', null, [
                'label' => 'online',
                'translation_domain' => 'LyssalBlogBundle'
            ])
            ->add('published_from', null, [
                'label' => 'published_from',
                'translation_domain' => 'LyssalBlogBundle'
            ])
            ->add('published_until', null, [
                'label' => 'published_until',
                'translation_domain' => 'LyssalBlogBundle'
            ])
            ->add('categories', null, [
                'label' => 'categories',
                'translation_domain' => 'LyssalBlogBundle'
            ])
        ;
    }


    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => $this->postClassname,
            'translation_domain' => 'LyssalBlogBundle'
        ]);
    }
}
