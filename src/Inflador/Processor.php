<?php

namespace Inflador;

abstract class Processor
{
    private $sourceDir;
    private $destinationDir;

    public function setSourceDir($sourceDir)
    {
        $this->sourceDir = $sourceDir;
    }

    public function getSourceDir()
    {
        return $this->sourceDir;
    }

    public function setDestinationDir($destinationDir)
    {
        $this->destinationDir = $destinationDir;
    }

    public function getDestinationDir()
    {
        return $this->destinationDir;
    }

    abstract public function process();
}