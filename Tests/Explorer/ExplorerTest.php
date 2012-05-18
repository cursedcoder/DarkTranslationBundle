<?php

namespace Dark\TranslationBundle\Tests\Explorer;

use Dark\TranslationBundle\Explorer\Explorer;

class ExplorerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldLocateGoodData()
    {
        $firstFixture = $this->getMockedFilesArray(10);

        $explorer = $this->getExplorerMock();

        $explorer->expects($this->exactly(2))
            ->method('validatePath')
            ->will($this->returnValue(true));

        $explorer->expects($this->once())
            ->method('getFinder')
            ->will($this->returnValue($firstFixture));

        $data = $explorer->locate('bla bla bla');

        $this->assertCount(10, $data);
    }

    /**
     * @test
     */
    public function shouldCutBreadcrumbs()
    {
        $explorer = $this->getExplorerMock();

        $path = 'my/mega/super/path';
        $crumbs = $explorer->breadcrumbs($path);

        $this->assertCount(4, $crumbs);
        $this->assertEquals(array($path => 'path'), $crumbs[3]);
    }

    /**
     * @test
     */
    public function shouldShowGoodData()
    {
        $fixture = 'mega turbo power fixture';

        $explorer = $this->getExplorerMock();

        $explorer->expects($this->once())
            ->method('get')
            ->will($this->returnValue($fixture));

        $data = $explorer->show('sample path');

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

        $explorer = $this->getExplorerMock();

        $explorer->expects($this->at(1))
            ->method('get')
            ->will($this->returnValue($firstFixture));

        $explorer->expects($this->at(2))
            ->method('get')
            ->will($this->returnValue($secondFixture));

        $data = $explorer->info($path);

        $this->assertCount(4, $data);
        $this->assertEquals($data['source'], $firstFixture);
        $this->assertEquals($data['result'], $secondFixture);
        $this->assertEquals($data['path'], $path);
    }

    /**
     * @test
     */
    public function shouldget()
    {
        $explorer = $this->getExplorerMock();
        $explorer->validatePath(__FILE__);
    }

    /**
     * @test
     */
    public function shouldValidateLevel()
    {
        $config = array(__DIR__."/../Utils/", '', '', '');

        $explorer = $this->getExplorerMock($config, false);
        $check = $explorer->validatePath(__FILE__);

        $this->assertEquals(__FILE__, $check);
    }

    /**
     * @test
     * @expectedException Dark\TranslationBundle\Explorer\Exception
     */
    public function shouldNotValidateNotExistsLevel()
    {
        $config = array('/root/not/exist/path', '', '', '');

        $explorer = $this->getExplorerMock($config, false);
        $explorer->validatePath('/root/not/exist/path/really');
    }

    /**
     * @test
     * @expectedException Dark\TranslationBundle\Explorer\Exception
     */
    public function shouldNotAccessUpperLevel()
    {
        $config = array(__DIR__."/../Utils/", '', '', '');

        $explorer = $this->getExplorerMock($config, false);
        $explorer->validateLevel(__DIR__.'/../bootstrap.php');
    }

    /**
     * @test
     * @expectedException Dark\TranslationBundle\Explorer\Exception
     */
    public function shouldNotGetNotExistsFile()
    {
        $config = array('/root/not/exist/file', '', '', '');

        $explorer = $this->getExplorerMock($config, false);
        $explorer->get('/root/not/exist/file/really.txt');
    }

    /**
     * @test
     * @expectedException Dark\TranslationBundle\Explorer\Exception
     */
    public function shouldNotGetUpperFile()
    {
        $config = array(__DIR__."/../Utils/", '', '', '');

        $explorer = $this->getExplorerMock($config, false);
        $explorer->get(__DIR__.'/../bootstrap.php', true);
    }

    /**
     * @test
     * @expectedException Dark\TranslationBundle\Explorer\Exception
     */
    public function shouldNotSaveUpperFile()
    {
        $config = array(__DIR__."/../Utils/", '', '', '');

        $explorer = $this->getExplorerMock($config, false);
        $explorer->save('/../../check.txt', '121');
    }

    protected function getExplorerMock($fixture = null, $fileMethods = true)
    {
        $methods = array('getFinder');

        if ($fileMethods) {
            $methods = array_merge($methods, array('get', 'save', 'validatePath'));
        }

        return $this->getMockBuilder('Dark\\TranslationBundle\\Explorer\\Explorer')
            ->setMethods($methods)
            ->setConstructorArgs(array($fixture ? $fixture : range(1, 4)))
            ->getMock();
    }

    protected function getMockedFilesArray($count, $flag = false)
    {
        $files = array();

        for ($i = 0; $i < $count; $i++) {
            $file = $this->getMockBuilder('SplFileInfo')
                ->disableOriginalConstructor()
                ->setMethods(array('getBasename', 'getMTime', 'getRealPath', 'isDir'))
                ->getMock();

            $files[] = $file;
        }

        return $files;
    }

}