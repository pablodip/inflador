<?php

namespace Inflador\Tests\Plugin\InfladorPlugin\Processor;

use Inflador\Tests\TestCase;
use Inflador\Plugin\InfladorPlugin\Processor\StaticExplicitFilesProcessor;

class StaticExplicitFilesProcessorTest extends TestCase
{
    private $filesystem;

    protected function setUp()
    {
        $this->filesystem = $this->createFilesystemMock();
    }

    public function testProcessShouldCopyTheFilesWithTheProcessor()
    {
        $files = array('foo', 'bar');

        foreach ($files as $file) {
            $this->mockShouldReceiveOrderedWith($this->filesystem, 'copyFile', $file);
        }

        $processor = $this->createProcessor($files);
        $processor->process();
    }

    private function createProcessor($files)
    {
        return new StaticExplicitFilesProcessor($this->filesystem, $files);
    }
}