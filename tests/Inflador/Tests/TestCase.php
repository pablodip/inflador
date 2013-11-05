<?php

namespace Inflador\Tests;

class TestCase extends \PHPUnit_Framework_TestCase
{
    protected function createFinderMock()
    {
        return $this->mock('Inflador\Finder');
    }

    protected function createFilesystemMock()
    {
        return $this->mock('Inflador\Filesystem');
    }

    protected function mock($class)
    {
        return \Mockery::mock($class);
    }

    protected function mockShouldReceiveOrderedWith($mock, $method, $with)
    {
        $mock->shouldReceive($method)
             ->once()
             ->with($with)
             ->ordered();
    }

    protected function putFiles($dir, $files)
    {
        foreach ($files as $file) {
            $this->putFile($dir, $file);
        }
    }

    protected function putFile($dir, $file, $content = null)
    {
        file_put_contents($this->filePath($dir, $file), $content);
    }

    protected function filePath($dir, $file)
    {
        return sprintf('%s/%s', $dir, $file);
    }
}