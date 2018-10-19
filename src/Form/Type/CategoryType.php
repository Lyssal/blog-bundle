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
 * The Category form.
 */
class CategoryType extends AbstractType
{
    /**
     * @var string The Post classname
     */
    protected $categoryClassname;


    /**
     * Constructor.
     *
     * @param string $categoryClassname The Post classname
     */
    public function __construct($categoryClassname)
    {
        $this->categoryClassname = $categoryClassname;
    }


    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('page', PageType::class, [
                'label' => 'page',
                'translation_domain' => 'LyssalSeoBundle'
            ])
            ->add('parent', null, [
                'label' => 'parent',
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
            'data_class' => $this->categoryClassname,
            'translation_domain' => 'LyssalBlogBundle'
        ]);
    }
}
