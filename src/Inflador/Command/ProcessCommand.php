<?php

namespace Inflador\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProcessCommand extends Command
{
    protected function configure()
    {
        $this->setName('process');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->getProcessors() as $processor) {
            $processor->process();
        }
    }

    private function getProcessors()
    {
        $container = $this->getContainer();

        $processors = array();
        foreach ($this->getProcessorServices() as $id) {
            $processors[] = $container->get($id);
        }

        return $processors;
    }

    private function getProcessorServices()
    {
        return array_keys($this->getContainer()->findTaggedServiceIds('inflador.processor'));
    }

    private function getContainer()
    {
        return $this->getApplication()->getContainer();
    }
}