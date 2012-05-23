<?php

namespace Dark\TranslationBundle\Browser;

use Symfony\Component\Finder\Finder;

class Tracker
{
    private $baseDir;
    private $info;
    private $finder;

    const NOT_CHANGED = 0;
    const IS_CHANGED = 1;

    public function __construct($baseDir, Finder $finder)
    {
        $this->baseDir = $baseDir;
        $this->finder = $finder;

        $this->info = $this->update($baseDir . '/info.dat');
    }

    public function track($path)
    {
        $path = realpath($path);
        $md5 = md5_file($path);
        $mark = isset($this->info[$path]) ? $this->info[$path] : false;

        if ($md5 != $mark) {
            $this->info[$path] = $md5;
            $this->dump();
        }
    }

    public function check($path)
    {
        $mark = isset($this->info[$path]) ? $this->info[$path] : false;

        if ($mark == md5_file($path)) {
            return self::NOT_CHANGED;
        } else {
            return self::IS_CHANGED;
        }
    }

    public function update($path)
    {
        $info = array();

        $data = file_get_contents($path);
        $data = explode(PHP_EOL, $data);
        $data = array_map("rtrim", $data);

        foreach ($data as $line) {
            list($class, $md5) = explode(';', $line);
            $info[$class] = $md5;
        }

        return $info;
    }

    public function dump()
    {
        $tree = array();

        foreach ($this->info as $path => $md5) {
            $tree[] = $path . ';' . $md5;
        }

        file_put_contents($this->baseDir . '/info.dat', implode(PHP_EOL, $tree));
    }
}