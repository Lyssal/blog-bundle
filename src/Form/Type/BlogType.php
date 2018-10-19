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
 * The Blog form.
 */
class BlogType extends AbstractType
{
    /**
     * @var string The Blog classname
     */
    protected $blogClassname;


    /**
     * Constructor.
     *
     * @param string $blogClassname The Blog classname
     */
    public function __construct($blogClassname)
    {
        $this->blogClassname = $blogClassname;
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
        ;
    }


    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => $this->blogClassname,
            'translation_domain' => 'LyssalBlogBundle'
        ]);
    }
}
