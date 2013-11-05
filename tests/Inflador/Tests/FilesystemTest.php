<?php

namespace Inflador\Tests;

use Inflador\Filesystem;
use org\bovigo\vfs\vfsStream;

class FilesystemTest extends \PHPUnit_Framework_TestCase
{
    private $sourceDir;
    private $destinationDir;
    private $filesystem;

    protected function setUp()
    {
        vfsStream::setup('root');

        $this->sourceDir = $this->createTempDir();
        $this->destinationDir = $this->createTempDir();

        $this->filesystem = new Filesystem($this->sourceDir, $this->destinationDir);
    }

    private function createTempDir()
    {
        $dir = vfsStream::url('root').DIRECTORY_SEPARATOR.uniqid();
        mkdir($dir);

        return $dir;
    }

    public function testCopyFileShouldCopyAFile()
    {
        $this->writeFile($this->sourceDir, 'file1', 'foo');

        $this->filesystem->copyFile('file1');

        $file1 = $this->createPath($this->destinationDir, 'file1');

        $this->assertFileExists($file1);
        $this->assertSame('foo', file_get_contents($file1));
    }

    public function testCopyFileShouldCreateTheDirectoryIfItDoesNotExist()
    {
        $this->writeFile($this->sourceDir, 'new/dir/file', 'foo');

        $this->filesystem->copyFile('new/dir/file');

        $file = $this->createPath($this->destinationDir, 'new/dir/file');

        $this->assertFileExists($file);
        $this->assertSame('foo', file_get_contents($file));
    }

    public function testCopyFileShouldNotCopyOtherFiles()
    {
        $this->writeFile($this->sourceDir, 'file1', 'foo');
        $this->writeFile($this->sourceDir, 'file2', 'bar');

        $this->filesystem->copyFile('file1');

        $file2 = $this->createPath($this->destinationDir, 'file2');
        $this->assertFileNotExists($file2);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testCopyShouldThrowARuntimeExceptionIfTheSourceFileDoesNotExist()
    {
        $this->filesystem->copyFile('file');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testCopyShouldThrowARuntimeExceptionIfTheDestinationFileAlreadyExists()
    {
        $this->writeFile($this->sourceDir, 'file', 'foo');
        $this->writeFile($this->destinationDir, 'file', 'foo');

        $this->filesystem->copyFile('file');
    }

    public function testWriteFileShouldWriteAFileWithTheContent()
    {
        $this->filesystem->writeFile('file', 'foo');

        $file = $this->createPath($this->destinationDir, 'file');
        $this->assertFileExists($file);
        $this->assertSame('foo', file_get_contents($file));
    }

    public function testWriteFileShouldCreateTheDirectoryIfItDoesNotExist()
    {
        $this->filesystem->writeFile('new/dir/file', 'foo');

        $file = $this->createPath($this->destinationDir, 'new/dir/file');
        $this->assertFileExists($file);
        $this->assertSame('foo', file_get_contents($file));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testWriteFileShouldThrowARuntimeExceptionIfTheDestinationFileAlreadyExists()
    {
        $this->filesystem->writeFile('file', 'foo');
        $this->filesystem->writeFile('file', 'foo');
    }

    private function writeFile($dir, $file, $content)
    {
        $path = $this->createPath($dir, $file);

        $this->mkdir(dirname($path));
        file_put_contents($path, $content);
    }

    private function createPath($dir, $file)
    {
        return $dir.DIRECTORY_SEPARATOR.$file;
    }

    private function mkdir($dir)
    {
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }
}