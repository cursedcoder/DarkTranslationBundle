<?php

namespace Dark\TranslationBundle\Explorer;

use Symfony\Component\Finder\Finder;

class Explorer
{
    private $baseDir;
    private $sourceDir;
    private $resultDir;
    private $buildDir;

    public function __construct($config)
    {
        list(
          $this->baseDir,
          $this->sourceDir,
          $this->resultDir,
          $this->buildDir
        ) = $config;

        $this->baseDir = $this->processPath($this->baseDir);
    }

    public function locate($path)
    {
        $files = array();

        $sourcePath = $this->validatePath($this->sourceDir . '/' . $path);
        $resultPath = $this->validatePath($this->resultDir . '/' . $path);

        $finder = $this->getFinder($sourcePath);

        foreach ($finder as $info) {
            $file = new File();

            $checkTranslation = file_exists(str_replace($sourcePath, $resultPath, $info->getRealPath()));

            $file->setName($info->getBasename());
            $file->setCreatedAt($info->getMTime());
            $file->setIsDir($info->isDir());
            $file->setIsTranslated($checkTranslation);

            $files[] = $file;
        }

        return $files;
    }

    public function breadcrumbs($path)
    {
        $crumbs = array();

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

    public function show($path)
    {
        if (strstr($path, '.rst')) {
            $path = str_replace('.rst', '.html', $path);
        }

        $data = $this->get($this->buildDir . '/' . $path);

        return $data;
    }

    public function info($path)
    {
        if (strstr($path, '.html')) {
            $path = str_replace('.html', '.rst', $path);
        }

        $resultPath = $this->resultDir . '/' . $path;
        $sourcePath = $this->sourceDir . '/' . $path;

        if (!file_exists($resultPath)) {
            $this->save($path, null);
        }

        $info['source'] = $this->get($sourcePath);
        $info['result'] = $this->get($resultPath);

        $dir = explode('/', $path);
        array_pop($dir);
        $info['dir'] = implode('/', $dir);

        $info['path'] = $path;

        return $info;
    }

    public function get($path)
    {
        $this->validatePath($path);
        $this->validateLevel($path);

        return file_get_contents($path);
    }

    public function save($path, $data)
    {
        $resultPath = $this->resultDir . '/' . $path;

        $this->validateLevel($resultPath);

        return file_put_contents($resultPath, $data);
    }

    public function createDir($path)
    {
        $path = $this->resultDir . '/' . $path;

        if (file_exists($path)) {
            throw new \Exception('Directory ' . $path . ' is already exists.');
        }

        mkdir($path, 0755);
    }

    public function validatePath($path)
    {
        $path = $this->processPath($path);

        if (!file_exists($path)) {
            throw new Exception('File ' . $path . ' is not exists.');
        }

        return $path;
    }

    public function validateLevel($path)
    {
        $path = $this->processPath($path);

        if (!strstr($path, $this->baseDir)) {
            throw new Exception('You have no access to this level.');
        }

        return $path;
    }

    protected function processPath($path)
    {
        $crumbs = explode('/', $path);
        $path = array();

        foreach ($crumbs as $level) {
            switch ($level) {
                default:
                    $path[] = $level;
                    break;

                case '..':
                    array_pop($path);
                    break;

                case '.': continue;
            }
        }

        $path = implode('/', $path);

        return $path;
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
            ->sortByType()
        ;
    }
}