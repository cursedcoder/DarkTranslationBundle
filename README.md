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