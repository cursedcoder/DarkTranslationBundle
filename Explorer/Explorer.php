<?php

namespace Dark\TranslationBundle\Explorer;

use Symfony\Component\Finder\Finder;

/**
 * Main class of bundle, provides file browser functional
 *
 * @author Evgeniy Guseletov <d46k16@gmail.com>
 */
class Explorer
{
    private $baseDir;
    private $sourceDir;
    private $resultDir;
    private $buildDir;

    /**
     * Constructor
     *
     * @param array $config
     */
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

    /**
     * Fetches directory structure from sourcePath and comparing with resultPath
     *
     * @param $path
     * @return array
     */
    public function locate($path)
    {
        $documents = array();

        $sourcePath = $this->validatePath($this->sourceDir . '/' . $path);
        $resultPath = $this->validatePath($this->resultDir . '/' . $path);

        $finder = $this->getFinder($sourcePath);

        foreach ($finder as $info) {
            $document = new Document();

            $checkTranslation = file_exists(str_replace($sourcePath, $resultPath, $info->getRealPath()));

            $document->setName($info->getBasename());
            $document->setCreatedAt($info->getMTime());
            $document->setIsDir($info->isDir());
            $document->setIsTranslated($checkTranslation);

            $documents[] = $document;
        }

        return $documents;
    }

    /**
     * Makes a breadcrumbs for given path
     *
     * @param $path
     * @return array
     */
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

    /**
     * Renders a built html documentation file
     *
     * @param $path
     * @return string
     */
    public function show($path)
    {
        if (strstr($path, '.rst')) {
            $path = str_replace('.rst', '.html', $path);
        }

        $data = $this->get($this->buildDir . '/' . $path);

        return $data;
    }

    /**
     * Fetches source and result documentation files for given path
     *
     * @param $path
     * @return array
     */
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

    /**
     * Gets file contents
     *
     * @param $path
     * @return string
     */
    public function get($path)
    {
        $this->validatePath($path);
        $this->validateLevel($path);

        return file_get_contents($path);
    }

    /**
     * Saves documentation file
     *
     * @param $path
     * @param $data
     * @return int
     */
    public function save($path, $data)
    {
        $resultPath = $this->resultDir . '/' . $path;

        $this->validateLevel($resultPath);

        return file_put_contents($resultPath, $data);
    }

    /**
     * Makes non-existent directories
     *
     * @param $path
     * @throws \Exception
     */
    public function createDir($path)
    {
        $path = $this->resultDir . '/' . $path;

        if (file_exists($path)) {
            throw new \Exception('Directory ' . $path . ' is already exists.');
        }

        mkdir($path, 0755);
    }

    /**
     * Validates a path for existing
     *
     * @param $path
     * @return array|string
     * @throws Exception
     */
    public function validatePath($path)
    {
        $path = $this->processPath($path);

        if (!file_exists($path)) {
            throw new Exception('File ' . $path . ' is not exists.');
        }

        return $path;
    }

    /**
     * Validates a path regarding to basePath
     *
     * @param $path
     * @return array|string
     * @throws Exception
     */
    public function validateLevel($path)
    {
        $path = $this->processPath($path);

        if (!strstr($path, $this->baseDir)) {
            throw new Exception('You have no access to this level.');
        }

        return $path;
    }

    /**
     * This function is same as realpath() but not checks for existing
     *
     * @param $path
     * @return array|string
     */
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