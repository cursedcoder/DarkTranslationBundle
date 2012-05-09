<?php

namespace Dark\TranslationBundle\Utils;

class FileHelper
{
    public function checkPath($path, $flag = false)
    {
        $check = realpath($path);

        if ($flag) {
            return $check ? true : false;
        }
        if (false === $check) {
            throw new FileException('File ' . $path . ' is not exist. You probably need to generate sphinx documents first.');
        }

        return $check;
    }

    public function getFile($path)
    {
        if (!file_exists($path)) {
            throw new FileException('File ' . $path . ' is not exist.');
        }

        return file_get_contents($path);
    }
}