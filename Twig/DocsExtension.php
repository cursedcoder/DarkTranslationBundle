<?php

namespace Dark\TranslationBundle\Twig;

use Twig_Extension;
use Twig_Filter_Method;
use Twig_Function_Method;

class DocsExtension extends Twig_Extension
{
    private $buildDir;

    public function __construct($buildDir)
    {
        $this->buildDir = $buildDir;
    }

    public function getFunctions()
    {
        return array(
            'isBuilt' => new Twig_Function_Method($this, 'isBuilt'),
        );
    }

    public function isBuilt($path)
    {
        if (strstr($path, '.inc')) {
            return false;
        }

        $buildPath = $this->buildDir . '/' . str_replace('.rst', '.html', $path);

        return file_exists($buildPath);
    }

    public function getName()
    {
        return 'docs_extension';
    }
}