# Syndication


## Atom

Use this route to generate the Atom feed:

```twig
<a href="{{ path('lyssal_blog_syndication_atom', { 'blog': blog.id }) }}">Atom</a>
```


## RSS

Use this route to generate the RSS feed:

```twig
<a href="{{ path('lyssal_blog_syndication_rss', { 'blog': blog.id }) }}">RSS</a>
```
