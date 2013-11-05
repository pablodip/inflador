<?php

namespace Inflador\Tests;

use Inflador\Finder;
use org\bovigo\vfs\vfsStream;

class FinderTest extends TestCase
{
    private $dir;
    private $finder;

    protected function setUp()
    {
        vfsStream::setup('root');
        $this->dir = vfsStream::url('root').'/source';
        mkdir($this->dir);

        $this->finder = new Finder($this->dir);
    }

    public function testNameShouldAddAName()
    {
        $this->finder->name('foo');

        $this->assertSame(array('foo'), $this->finder->getNames());
    }

    public function testNameShouldAllowSeveralCalls()
    {
        $this->finder->name('foo');
        $this->finder->name('bar');

        $this->assertSame(array('foo', 'bar'), $this->finder->getNames());
    }

    public function testNotnameShouldAddANotname()
    {
        $this->finder->notName('foo');

        $this->assertSame(array('foo'), $this->finder->getNotnames());
    }

    public function testNotnameShouldAllowSeveralCalls()
    {
        $this->finder->notName('foo');
        $this->finder->notName('bar');

        $this->assertSame(array('foo', 'bar'), $this->finder->getNotnames());
    }

    public function testFindShouldFindFilesWithOneName()
    {
        $this->putFiles($this->dir, array('foo.css', 'bar.css', 'foo.txt'));

        $this->finder->name('*.css');

        $this->assertSame(array('foo.css', 'bar.css'), $this->finder->find());
    }

    public function testFindShouldFindFilesWithSeveralName()
    {
        $this->putFiles($this->dir, array('foo.css', 'foo.jpg', 'bar.txt'));

        $this->finder->name('*.css');
        $this->finder->name('*.txt');

        $this->assertSame(array('foo.css', 'bar.txt'), $this->finder->find());
    }

    public function testFindShouldFindFilesWithOneNotName()
    {
        $this->putFiles($this->dir, array('foo.css', 'foo.jpg', 'bar.txt'));

        $this->finder->notName('foo.jpg');

        $this->assertSame(array('foo.css', 'bar.txt'), $this->finder->find());
    }

    public function testFindShouldFindFilesWithSeveralNotNames()
    {
        $this->putFiles($this->dir, array('foo.css', 'foo.jpg', 'bar.txt'));

        $this->finder->notName('foo.jpg');
        $this->finder->notName('bar.*');

        $this->assertSame(array('foo.css'), $this->finder->find());
    }
}