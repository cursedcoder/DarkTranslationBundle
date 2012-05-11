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
        $secondFixture = $this->getMockedFilesArray(5, true);

        $helper = $this->getHelperMock();

        $helper->expects($this->exactly(2))
            ->method('validatePath')
            ->will($this->returnValue(true));

        $explorer = $this->getExplorerMock($helper);

        $explorer->expects($this->at(0))
            ->method('getFinder')
            ->will($this->returnValue($firstFixture));

        $explorer->expects($this->at(1))
            ->method('getFinder')
            ->will($this->returnValue($secondFixture));

        $data = $explorer->locate('bla bla bla');

        $this->assertCount(2, $data);
        $this->assertCount(10, $data['source']);
        $this->assertCount(5, $data['result']);
    }

    /**
     * @test
     */
    public function shouldCutBreadcrumbs()
    {
        $helper = $this->getHelperMock();
        $explorer = $this->getExplorerMock($helper);

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

        $helper = $this->getHelperMock();

        $helper->expects($this->once())
            ->method('getFile')
            ->will($this->returnValue($fixture));

        $explorer = $this->getExplorerMock($helper);

        $data = $explorer->show('sample path');

        $this->assertEquals($fixture, $data);
    }

    protected function getExplorerMock($helper)
    {
        return $this->getMockBuilder('Dark\\TranslationBundle\\Explorer\\Explorer')
            ->setMethods(array('getFinder'))
            ->setConstructorArgs(array($helper, 'one', 'two', 'three'))
            ->getMock();
    }

    protected function getHelperMock()
    {
        return $this->getMockBuilder('Dark\\TranslationBundle\\Utils\\FileHelper')
            ->disableOriginalConstructor()
            ->setMethods(array('getFile', 'validatePath'))
            ->getMock();
    }

    protected function getMockedFilesArray($count, $flag = false)
    {
        $files = array();

        for ($i = 0; $i < $count; $i++) {
            $file = $this->getMockBuilder('SplFileInfo')
                ->disableOriginalConstructor()
                ->setMethods(array('getBasename'))
                ->getMock();

            $file->expects($flag ? $this->once() : $this->never())
                ->method('getBasename')
                ->will($this->returnValue('file_' . rand(10000, 99999)));

            $files[] = $file;
        }

        return $files;
    }

}