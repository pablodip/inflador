<?php

namespace Inflador\Tests;

use Inflador\Processor;

class MyProcessor extends Processor
{
    public function process()
    {
    }
}

class ProcessorTest extends \PHPUnit_Framework_TestCase
{
    private $processor;

    protected function setUp()
    {
        $this->processor = new MyProcessor();
    }

    public function testGetSourceDirShouldReturnTheSourceDir()
    {
        $dir = __DIR__;
        $this->processor->setSourceDir($dir);
        $this->assertSame($dir, $this->processor->getSourceDir());
    }

    public function testGetDestinationDirShouldReturnTheDestinationDir()
    {
        $dir = __DIR__;
        $this->processor->setDestinationDir($dir);
        $this->assertSame($dir, $this->processor->getDestinationDir());
    }
}