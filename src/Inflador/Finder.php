<?php

namespace Inflador;

use Symfony\Component\Finder\Finder as SymfonyFinder;

class Finder
{
    private $dir;
    private $names = array();
    private $notNames = array();

    public function __construct($dir)
    {
        $this->dir = $dir;
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

        $this->finderPassValues($finder);

        return $finder;
    }

    private function finderPassValues($finder)
    {
        \f\feach(function ($values, $function) use ($finder) {
            \f\feach(array($finder, $function), $values);
        }, $this->finderValuesToPass());
    }

    private function finderValuesToPass()
    {
        return array(
            'name'    => $this->names,
            'notName' => $this->notNames
        );
    }

    private function relativizeFiles($files)
    {
        return \f\values(\f\map(\f\method('getRelativePathName'), $files));
    }
}