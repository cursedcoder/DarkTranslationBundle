<?php

namespace Dark\TranslationBundle\Tests\Editor;

use Dark\TranslationBundle\Editor\Editor;

class EditorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldReturnGoodInfo()
    {
        $firstFixture = 'firstData';
        $secondFixture = 'secondData';
        $path = 'asd asd';

        $helper = $this->getMockBuilder('Dark\\TranslationBundle\\Utils\\FileHelper')
            ->disableOriginalConstructor()
            ->setMethods(array('getFile', 'saveFile'))
            ->getMock();

        $helper->expects($this->at(1))
            ->method('getFile')
            ->will($this->returnValue($firstFixture));

        $helper->expects($this->at(2))
            ->method('getFile')
            ->will($this->returnValue($secondFixture));

        $editor = new Editor($helper, 'asd', 'asd', 'asd');
        $data = $editor->info($path);

        $this->assertCount(3, $data);
        $this->assertEquals($data['source'], $firstFixture);
        $this->assertEquals($data['result'], $secondFixture);
        $this->assertEquals($data['path'], $path);
    }
}