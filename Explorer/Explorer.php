<?php

namespace Dark\TranslationBundle\Explorer;

use Symfony\Component\Finder\Finder;
use Dark\TranslationBundle\Utils\FileHelper;

class Explorer
{
    private $helper;
    private $sourcePath;
    private $resultPath;
    private $buildPath;

    public function __construct(FileHelper $helper, $sourcePath, $resultPath, $buildPath)
    {
        $this->helper = $helper;

        $this->sourcePath = $sourcePath;
        $this->resultPath = $resultPath;
        $this->buildPath = $buildPath;
    }

    public function locate($path)
    {
        $sourcePath = $this->helper->checkPath($this->sourcePath . '/' . $path);
        $resultPath = $this->helper->checkPath($this->resultPath . '/' . $path);

        $source = $this->getFinder($sourcePath);
        $finder = $this->getFinder($resultPath);

        $result = array();

        foreach ($finder as $file) {
            $result[$file->getBasename()] = true;
        }

        return array('source' => $source, 'result' => $result);
    }

    public function breadcrumbs($path)
    {
        $crumbs = array(array('' => 'Home'));

        if ('/' === $path) {
            return $crumbs;
        }

        $list = array();
        $data = explode('/', $path);

        foreach ($data as $crumb) {
            $list[] = $crumb;
            $crumbs[] = array(implode('/', $list) => $crumb);
        }

        return $crumbs;
    }

    public function createDir($path)
    {
        $path = $this->resultPath . '/' . $path;

        if (file_exists($path)) {
            throw new \Exception('Directory ' . $path . ' is already exist.');
        }

        mkdir($path, 0755);
    }

    public function show($path)
    {
        if (strstr($path, '.rst')) {
            $path = str_replace('.rst', '.html', $path);
        }

        $path = $this->helper->checkPath($this->buildPath . '/' . $path);
        $data = $this->helper->getFile($path);

        return $data;
    }

    protected function getFinder($path)
    {
        return Finder::create()
            ->in($path)
            ->ignoreDotFiles(true)
            ->ignoreVCS(true)
            ->depth(0)
            ->notName('*.markdown')
            ->exclude('images')
            ->sortByType();
    }
}