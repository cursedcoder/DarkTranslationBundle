# DarkTranslationBundle [![Build Status](https://secure.travis-ci.org/cursedcoder/DarkTranslationBundle.png?branch=master)](http://travis-ci.org/cursedcoder/DarkTranslationBundle)

This Symfony2 bundle allows you to easily translate symfony documentation into other languages.

### What's inside?

* Combined file manager
* Fancy double-editor with combined text-scroll
* Built-in symfony.com theme for docs
* Commands for generating and fetching docs
* And much more…

## Installation

Add DarkTranslationBundle to your composer.json:

```js
{
    "require": {
        "cursedcoder/dark-translation-bundle": "*"
    }
}
```

Register bundle in your `app/AppKernel.php`:

```php
<?php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Dark\TranslationBundle\DarkTranslationBundle(),
    );
)
```

Add bundle to your ``routing.yml``:

```jinja
dark_translation_bundle:
    resource: "@DarkTranslationBundle/Resources/config/routing.yml"
```

Set up an url for your fork and local path for docs that you would like to translate in ``config.yml``:

```jinja
# app/config.yml
dark_translation:
    repositories:
        # you should preset url for your fork here, example 'lang_tag: fork_url'
        ru: 'https://github.com/cursedcoder/symfony-docs-ru'
    source:
       base_dir: %kernel.root_dir%/../docs
       from: %kernel.root_dir%/../docs/symfony-docs
       to: %kernel.root_dir%/../docs/symfony-docs-ru # change lang_tag here
    build:
        path: %kernel.root_dir%/../docs/build
```

Then run command:

    php app/console dark-translation:fetch-docs en ru

replace ``ru`` with your lang-tag.

And do not forget about assets:

    php app/console assets:install web/ --symlink

## Documentation Building
Build all the html sources with sphinx in order to see how docs look like.
Be sure to have sphinx installed on your local machine. If not, run:

    easy_install -U sphinx
    easy_install -U sphinxcontrib-phpdomain

Now you are ready to generate the html sources with:

    php app/console dark-translation:build-docs

## Demo

You can see a production demo [here](http://docs.mitris.net/).
