<?php

namespace Dark\TranslationBundle\Tests\Browser;

use Dark\TranslationBundle\Browser\Browser;

/**
 * @author Evgeniy Guseletov <d46k16@gmail.com>
 */
class BrowserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldLocateGoodData()
    {
        $firstFixture = $this->getMockedFilesArray(10);

        $browser = $this->getBrowserMock();

        $browser->expects($this->exactly(2))
            ->method('validatePath')
            ->will($this->returnValue(true));

        $browser->expects($this->once())
            ->method('getFinder')
            ->will($this->returnValue($firstFixture));

        $data = $browser->locate('bla bla bla');

        $this->assertCount(10, $data);
    }

    /**
     * @test
     */
    public function shouldCutBreadcrumbs()
    {
        $browser = $this->getBrowserMock();

        $path = 'my/mega/super/path';
        $crumbs = $browser->breadcrumbs($path);

        $this->assertCount(4, $crumbs);
        $this->assertEquals(array($path => 'path'), $crumbs[3]);
    }

    /**
     * @test
     */
    public function shouldShowGoodData()
    {
        $fixture = 'mega turbo power fixture';

        $browser = $this->getBrowserMock();

        $browser->expects($this->once())
            ->method('get')
            ->will($this->returnValue($fixture));

        $data = $browser->show('sample path');

        $this->assertEquals($fixture, $data);
    }

    /**
     * @test
     */
    public function shouldReturnGoodInfo()
    {
        $firstFixture = 'firstData';
        $secondFixture = 'secondData';
        $path = 'asd asd';

        $browser = $this->getBrowserMock();

        $browser->expects($this->at(1))
            ->method('get')
            ->will($this->returnValue($firstFixture));

        $browser->expects($this->at(2))
            ->method('get')
            ->will($this->returnValue($secondFixture));

        $data = $browser->info($path);

        $this->assertCount(4, $data);
        $this->assertEquals($data['source'], $firstFixture);
        $this->assertEquals($data['result'], $secondFixture);
        $this->assertEquals($data['path'], $path);
    }

    /**
     * @test
     */
    public function shouldValidatePath()
    {
        $browser = $this->getBrowserMock();
        $browser->validatePath(__FILE__);
    }

    /**
     * @test
     */
    public function shouldValidateLevel()
    {
        $config = array(__DIR__."/../Utils/", '', '', '');

        $browser = $this->getBrowserMock($config, false);
        $check = $browser->validatePath(__FILE__);

        $this->assertEquals(__FILE__, $check);
    }

    /**
     * @test
     * @expectedException Dark\TranslationBundle\Browser\Exception
     */
    public function shouldNotValidateNotExistsLevel()
    {
        $config = array('/root/not/exist/path', '', '', '');

        $browser = $this->getBrowserMock($config, false);
        $browser->validatePath('/root/not/exist/path/really');
    }

    /**
     * @test
     * @expectedException Dark\TranslationBundle\Browser\Exception
     */
    public function shouldNotAccessUpperLevel()
    {
        $config = array(__DIR__."/../Utils/", '', '', '');

        $browser = $this->getBrowserMock($config, false);
        $browser->validateLevel(__DIR__.'/../bootstrap.php');
    }

    /**
     * @test
     * @expectedException Dark\TranslationBundle\Browser\Exception
     */
    public function shouldNotGetNotExistsFile()
    {
        $config = array('/root/not/exist/file', '', '', '');

        $browser = $this->getBrowserMock($config, false);
        $browser->get('/root/not/exist/file/really.txt');
    }

    /**
     * @test
     * @expectedException Dark\TranslationBundle\Browser\Exception
     */
    public function shouldNotGetUpperFile()
    {
        $config = array(__DIR__."/../Utils/", '', '', '');

        $browser = $this->getBrowserMock($config, false);
        $browser->get(__DIR__.'/../bootstrap.php', true);
    }

    /**
     * @test
     * @expectedException Dark\TranslationBundle\Browser\Exception
     */
    public function shouldNotSaveUpperFile()
    {
        $config = array(__DIR__."/../Utils/", '', '', '');

        $browser = $this->getBrowserMock($config, false);
        $browser->save('/../../check.txt', '121');
    }

    protected function getBrowserMock($fixture = null, $fileMethods = true)
    {
        $methods = array('getFinder', 'getTracker');

        if ($fileMethods) {
            $methods = array_merge($methods, array('get', 'save', 'validatePath'));
        }

        $browser = $this->getMockBuilder('Dark\\TranslationBundle\\Browser\\Browser')
            ->setMethods($methods)
            ->setConstructorArgs(array($fixture ? $fixture : range(1, 4)))
            ->getMock();

        return $browser;
    }

    protected function getMockedFilesArray($count, $flag = false)
    {
        $files = array();

        for ($i = 0; $i < $count; $i++) {
            $file = $this->getMockBuilder('SplFileInfo')
                ->disableOriginalConstructor()
                ->setMethods(array('getBasename', 'getMTime', 'getRealPath', 'isDir', 'isFile'))
                ->getMock();

            $files[] = $file;
        }

        return $files;
    }
}