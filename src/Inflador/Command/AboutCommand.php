<?php

namespace Inflador\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AboutCommand extends Command
{
    protected function configure()
    {
        $this->setName('about');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(<<<EOT
<info>Inflador - Static Site Generator for PHP</info>
EOT
        );
    }
}