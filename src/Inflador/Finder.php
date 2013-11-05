<?php

namespace Inflador;

use Symfony\Component\Finder\Finder as SymfonyFinder;

class Finder
{
    private $dir;
    private $names;
    private $notNames;

    public function __construct($dir)
    {
        $this->dir = $dir;
        $this->names = array();
        $this->notNames = array();
    }

    public function name($name)
    {
        $this->names[] = $name;
    }

    public function getNames()
    {
        return $this->names;
    }

    public function notName($notName)
    {
        $this->notNames[] = $notName;
    }

    public function getNotNames()
    {
        return $this->notNames;
    }

    public function find()
    {
        $finder = $this->createFinder();
        $files = $this->relativizeFiles($finder);

        return $files;
    }

    private function createFinder()
    {
        $finder = new SymfonyFinder();
        $finder->in($this->dir);

        foreach ($this->names as $name) {
            $finder->name($name);
        }

        foreach ($this->notNames as $notName) {
            $finder->notName($notName);
        }

        return $finder;
    }

    private function relativizeFiles($files)
    {
        $relativized = array();
        foreach ($files as $file) {
            $relativized[] = $file->getRelativePathName();
        }

        return $relativized;
    }
}