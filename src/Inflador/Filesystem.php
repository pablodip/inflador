<?php

namespace Inflador;

class Filesystem
{
    private $sourceDir;
    private $destinationDir;

    public function __construct($sourceDir, $destinationDir)
    {
        $this->sourceDir = $sourceDir;
        $this->destinationDir = $destinationDir;
    }

    public function copyFile($file)
    {
        $sourceFile = $this->sourcePath($file);
        $destinationFile = $this->destinationPath($file);

        if (!file_exists($sourceFile)) {
            throw new \RuntimeException(sprintf('The source file "%s" does not exist.', $sourceFile));
        }

        $this->checkDestinationFileDoesNotExist($destinationFile);

        $this->mkdir(dirname($destinationFile));
        copy($sourceFile, $destinationFile);
    }

    public function writeFile($file, $content)
    {
        $destinationFile = $this->destinationPath($file);

        $this->checkDestinationFileDoesNotExist($destinationFile);

        $this->mkdir(dirname($destinationFile));
        file_put_contents($destinationFile, $content);
    }

    private function sourcePath($file)
    {
        return $this->createPath($this->sourceDir, $file);
    }

    private function destinationPath($file)
    {
        return $this->createPath($this->destinationDir, $file);
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

    private function checkDestinationFileDoesNotExist($destinationFile)
    {
        if (file_exists($destinationFile)) {
            throw new \RuntimeException(sprintf('The destination file "%s" already exists.', $destinationFile));
        }
    }
}