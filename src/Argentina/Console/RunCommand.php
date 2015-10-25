<?php

namespace Argentina\Console;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

use Argentina\Process\DumpProcess;

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

        try {
            $process = DumpProcess::get($output);
        } catch(Exception $e) {
            $output->writeln('<error>'.$e->getMessage().'</error>');
            return;
        }


        $helper->run($output, $process, 'The process failed :(', function ($type, $data) {
            if (Process::ERR === $type) {
                // ... do something with the stderr output

            } else {
                // ... do something with the stdout
            }
        });


    }
}