<?php

namespace Inflador\Tests\Plugin\InfladorPlugin\Processor;

use Inflador\Tests\TestCase;
use Inflador\Plugin\InfladorPlugin\Processor\StaticFilesByExtensionProcessor;

class StaticFilesByExtensionProcessorTest extends TestCase
{
    private $finder;
    private $filesystem;

    protected function setUp()
    {
        $this->finder = $this->createFinderMock();
        $this->filesystem = $this->createFilesystemMock();
    }

    public function testProcessShouldCopyTheFilesWithTheIndicatedExtensions()
    {
        $extensions = array('css', 'jpg');
        $files = array('foo.css', 'foo.jpg', 'bar.css');

        foreach ($extensions as $extension) {
            $this->mockShouldReceiveOrderedWith($this->finder, 'name', '*.'.$extension);
        }
        $this->finder->shouldReceive('find')->once()->andReturn($files)->ordered();

        foreach ($files as $file) {
            $this->mockShouldReceiveOrderedWith($this->filesystem, 'copyFile', $file);
        }

        $processor = $this->createProcessor($extensions);
        $processor->process();
    }

    public function testProcessShouldNotCopyFilesIfThereAreNoExtensions()
    {
        $processor = $this->createProcessor(array());
        $processor->process();
    }

    private function createProcessor($extensions)
    {
        return new StaticFilesByExtensionProcessor(
            $this->finder,
            $this->filesystem,
            $extensions
        );
    }
}