<?php

namespace Argentina\Console;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Process\ProcessBuilder;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class RunCommand extends \Symfony\Component\Console\Command\Command
{
    protected function configure()
    {
        $this
            ->setName('bk')
            ->setDescription('Run backup');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('process');
        $process = ProcessBuilder::create(['ls', '-a'])->getProcess();


        $helper->run($output, $process, 'The process failed :(', function ($type, $data) {
            if (Process::ERR === $type) {
                // ... do something with the stderr output

            } else {
                // ... do something with the stdout
            }
        });


    }
}