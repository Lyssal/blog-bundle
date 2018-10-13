# Installation


* Install with Composer

```sh
composer require lyssal/blog-bundle
```

* Register these bundles in your kernel file:

```
Lyssal\BlogBundle\LyssalBlogBundle
Lyssal\Doctrine\OrmBundle\LyssalDoctrineOrmBundle
Lyssal\EntityBundle\LyssalEntityBundle
```

* Overload the mapped super classes

* Updates the config parameters

All the `lyssal.blog.entity.*.class` parameters.
See the other parameters you can overload.

* Update your database

```sh
bin/console doctrine:schema:update --force
```

* Update your routes

For example:

```yaml
blog:
    resource: '@LyssalBlogBundle/Resources/config/routing.yml'
```
