<?php

namespace Dark\TranslationBundle\Tests\Utils;

use Dark\TranslationBundle\Utils\FileHelper;

class FileHelperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * ololo
     * @test
     */
    public function shouldGetFile()
    {
        $helper = new FileHelper();
        $helper->checkPath(__FILE__);
    }

    /**
     * ololo
     * @test
     */
    public function shouldCheckFile()
    {
        $helper = new FileHelper();
        $check = $helper->checkPath(__FILE__);

        $this->assertEquals(__FILE__, $check);
    }

    /**
     * @test
     * @expectedException Dark\TranslationBundle\Utils\FileException
     */
    public function shouldNotGetNotExistFile()
    {
        $helper = new FileHelper();
        $helper->checkPath('/root/not/exist/path/really');
    }

    /**
     * @test
     * @expectedException Dark\TranslationBundle\Utils\FileException
     */
    public function shouldNotCheckNotExistFile()
    {
        $helper = new FileHelper();
        $helper->getFile('/root/not/exist/file/really.txt');
    }
}