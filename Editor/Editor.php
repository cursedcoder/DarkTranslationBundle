<?php

namespace Dark\TranslationBundle\Editor;

use Dark\TranslationBundle\Utils\FileHelper;

class Editor
{
    private $helper;
    private $sourcePath;
    private $resultPath;

    public function __construct(FileHelper $helper, $sourcePath, $resultPath)
    {
        $this->helper = $helper;

        $this->sourcePath = $sourcePath;
        $this->resultPath = $resultPath;
    }

    public function info($path)
    {
        if (strstr($path, '.html')) {
            $path = str_replace('.html', '.rst', $path);
        }

        $resultPath = $this->resultPath . '/' . $path;
        $sourcePath = $this->sourcePath . '/' . $path;

        if (!$this->helper->checkPath($resultPath, true)) {
            file_put_contents($resultPath, null);
        }

        $info['source'] = $this->helper->getFile($sourcePath);
        $info['result'] = $this->helper->getFile($resultPath);
        $info['path'] = $path;

        return $info;
    }

    public function save($path, $data)
    {
        $resultPath = $this->resultPath . '/' . $path;
        file_put_contents($resultPath, $data);
    }
}