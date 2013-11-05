<?php

namespace Inflador\Plugin;

abstract class Plugin
{
    public function getNamespace()
    {
        $class = get_class($this);

        return substr($class, 0, strrpos($class, '\\'));
    }

    final public function getName()
    {
        $name = get_class($this);
        $pos = strrpos($name, '\\');

        return false === $pos ? $name :  substr($name, $pos + 1);
    }

    public function getContainerExtension()
    {
        $basename = preg_replace('/Plugin$/', '', $this->getName());

        $class = $this->getNamespace().'\\DependencyInjection\\'.$basename.'Extension';
        if (class_exists($class)) {
            return new $class();
        }
    }

    public function buildContainer($container)
    {
    }
}