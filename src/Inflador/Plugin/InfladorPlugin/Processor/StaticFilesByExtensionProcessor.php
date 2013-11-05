<?php

namespace Inflador\Plugin\InfladorPlugin\Processor;

use Inflador\Processor;
use Inflador\Finder;
use Inflador\Filesystem;

class StaticFilesByExtensionProcessor extends Processor
{
    private $finder;
    private $filesystem;
    private $extensions;

    public function __construct(Finder $finder, Filesystem $filesystem, array $extensions)
    {
        $this->finder = $finder;
        $this->filesystem = $filesystem;
        $this->extensions = $extensions;
    }

    public function process()
    {
        if ($this->thereAreNoExtensions()) {
            return;
        }

        $this->finderExtensions();

        foreach ($this->finder->find() as $file) {
            $this->filesystem->copyFile($file);
        }
    }

    private function thereAreNoExtensions()
    {
        return empty($this->extensions);
    }

    private function finderExtensions()
    {
        foreach ($this->extensions as $extension) {
            $this->finder->name($this->finderExtensionName($extension));
        }
    }

    private function finderExtensionName($extension)
    {
        return sprintf('*.%s', $extension);
    }
}