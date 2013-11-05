<?php

namespace Inflador\Tests;

use Inflador\ConfigFileReader;
use Symfony\Component\Yaml\Yaml;

class ConfigFileReaderTest extends \PHPUnit_Framework_TestCase
{
    private $fixturesDir;
    private $reader;

    protected function setUp()
    {
        $this->fixturesDir = __DIR__.'/fixtures';
        $this->reader = new ConfigFileReader();
    }

    public function testRadShouldReturnTheConfigFileContentParsedInYaml()
    {
        $file = $this->fixturesDir.'/inflador.yml';

        $expected = Yaml::parse($file);
        $result = $this->reader->read($file);

        $this->assertSame($expected, $result);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testReadShouldThrowARuntimeExceptionIfTheConfigFileDoesNotExist()
    {
        $this->reader->read($this->fixturesDir.'/foo');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testReadShouldThrowARuntimeExceptionIfTheConfigFileIsNotValid()
    {
        $this->reader->read($this->fixturesDir.'/inflador_not_valid.yml');
    }
}