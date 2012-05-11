<?php

namespace Dark\TranslationBundle\Tests\Utils;

use Dark\TranslationBundle\Utils\FileHelper;

class FileHelperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldGetFile()
    {
        $helper = new FileHelper(__DIR__);
        $helper->validatePath(__FILE__);
    }

    /**
     * @test
     */
    public function shouldValidateLevel()
    {
        $helper = new FileHelper(__DIR__."/../Utils/");
        $check = $helper->validatePath(__FILE__);

        $this->assertEquals(__FILE__, $check);
    }

    /**
     * @test
     * @expectedException Dark\TranslationBundle\Utils\FileException
     */
    public function shouldNotValidateNotExistsLevel()
    {
        $helper = new FileHelper('/root/not/exist/path');
        $helper->validatePath('/root/not/exist/path/really');
    }

    /**
     * @test
     * @expectedException Dark\TranslationBundle\Utils\FileException
     */
    public function shouldNotAccessUpperLevel()
    {
        $helper = new FileHelper(__DIR__."/../Utils/");
        $helper->validateLevel(__DIR__.'/../bootstrap.php');
    }

    /**
     * @test
     * @expectedException Dark\TranslationBundle\Utils\FileException
     */
    public function shouldNotGetNotExistsFile()
    {
        $helper = new FileHelper('/root/not/exist/file');
        $helper->getFile('/root/not/exist/file/really.txt');
    }

    /**
     * @test
     * @expectedException Dark\TranslationBundle\Utils\FileException
     */
    public function shouldNotGetUpperFile()
    {
        $helper = new FileHelper(__DIR__."/../Utils/");
        $helper->getFile(__DIR__.'/../bootstrap.php', true);
    }

    /**
     * @test
     * @expectedException Dark\TranslationBundle\Utils\FileException
     */
    public function shouldNotSaveUpperFile()
    {
        $helper = new FileHelper(__DIR__."/../Utils/");
        $helper->saveFile(__DIR__.'/../../check.txt', '121');
    }
}