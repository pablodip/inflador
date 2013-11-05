<?php

namespace Inflador\Plugin\InfladorPlugin\Processor;

use Inflador\Processor;
use Inflador\Filesystem;

class StaticExplicitFilesProcessor extends Processor
{
    private $filesystem;
    private $files;

    public function __construct(Filesystem $filesystem, array $files)
    {
        $this->filesystem = $filesystem;
        $this->files = $files;
    }

    public function process()
    {
        foreach ($this->files as $file) {
            $this->filesystem->copyFile($file);
        }
    }
}