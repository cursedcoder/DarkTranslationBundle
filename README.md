# DarkTranslationBundle [![Build Status](https://secure.travis-ci.org/cursedcoder/DarkTranslationBundle.png?branch=master)](http://travis-ci.org/cursedcoder/DarkTranslationBundle)

This Symfony2 bundle allow you to easily translate symfony documentation into other languages.

### What inside?

* Combined file manager
* Fancy double-editor with combined text-scroll
* Built-in symfony.com theme for docs
* Commands for generating and fetching docs
* And much more…

## Installation

Add DarkTranslationBundle in your composer.json

```js
{
    "require": {
        "cursedcoder/dark-translation-bundle": "*"
    }
}
```

Register the bundle in your `app/AppKernel.php`:

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

Set up url for your fork and local path for docs that you would to translate in ``config.yml``

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

Then run command ``php app/console dark-translation:fetch-docs en ru``, replace ``ru`` with yours lang-tag.

## Build Docs Command
If you want to see how docs is view, you can translate in into html with sphinx.
Be sure, that you have sphinx on your local machine. If not, run command:

    easy_install -U Sphinx

And then you are freely to generate html:

    php app/console dark-translation:build-docs

## Screenshots

![File browser](http://puu.sh/tvu3.jpg)

![Editor](http://puu.sh/tvuD.jpg)

![Docs view](http://puu.sh/tvv8.jpg)