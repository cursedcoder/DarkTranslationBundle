<?php

namespace Dark\TranslationBundle\Utils;

class FileHelper
{
    private $baseDir;

    public function __construct($baseDir)
    {
        $this->baseDir = $this->processPath($baseDir);
    }

    public function getFile($path)
    {
        $this->validatePath($path);
        $this->validateLevel($path);

        return file_get_contents($path);
    }

    public function saveFile($path, $data)
    {
        $this->validateLevel($path);

        return file_put_contents($path, $data);
    }

    public function validatePath($path)
    {
        $path = $this->processPath($path);

        if (!file_exists($path)) {
            throw new FileException('File ' . $path . ' is not exists.');
        }

        return $path;
    }

    public function validateLevel($path)
    {
        $path = $this->processPath($path);

        if (!strstr($path, $this->baseDir)) {
            throw new FileException('You have no access to this level.');
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
}