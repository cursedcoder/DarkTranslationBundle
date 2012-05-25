<?php

namespace Dark\TranslationBundle\Browser;

use Symfony\Component\Finder\Finder;

/**
 * Main class of bundle, provides file browser functional
 *
 * @author Evgeniy Guseletov <d46k16@gmail.com>
 */
class Browser
{
    private $baseDir;
    private $sourceDir;
    private $resultDir;
    private $buildDir;
    private $tracker;

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

        $this->sourceDir = $this->processPath($this->sourceDir);
        $this->baseDir = $this->processPath($this->baseDir);
        $this->tracker = $this->getTracker();
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

            if ($info->isFile()) {
                $checkChanges = $this->tracker->check($info->getRealPath());
                $document->setIsChanged($checkChanges);
            }

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
        $sourcePath = $this->sourceDir . '/' . $path;

        $this->validateLevel($resultPath);

        file_put_contents($resultPath, $data);

        $this->tracker->track($sourcePath);
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
     * Dump md5 file structure
     * @param $path
     */
    public function dumpChanges($path)
    {
        $changes = implode(PHP_EOL, $this->md5Tree($path));

        file_put_contents($this->baseDir . '/info.dat', $changes);

        $this->info = $this->tracker->update($this->baseDir . '/info.dat');
    }

    /**
     * Recursive fetch md5
     *
     * @param $path
     * @param array $hashes
     * @return array
     */
    protected function md5Tree($path, &$hashes = array())
    {
        $finder = $this->getFinder($path);

        foreach ($finder as $file) {
            if ($file->isDir()) {
                $hashes = $this->md5Tree($file->getRealPath(), $hashes);
            } else {
                $hashes[] = $file->getRealPath() . ';' . md5_file($file->getRealPath());
            }
        }

        return $hashes;
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

    protected function getTracker()
    {
        return new Tracker($this->baseDir, $this->getFinder($this->sourceDir));
    }
}