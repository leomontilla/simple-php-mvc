<?php

namespace MVC\Console\Command;

use MVC\Console\Input\InputArgument;
use MVC\Console\Input\InputDefinition;
use MVC\Console\Input\InputInterface;
use MVC\Console\Input\InputOption;
use MVC\Console\Output\OutputInterface;

/**
 * Description of ListCommand
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
class ListCommand extends Command
{
    
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('list')
             ->setHelp(<<<EOF
The <info>%command.name%</info> command lists all commands:

  <info>php %command.full_name%</info>

You can also display the commands for a specific namespace:

  <info>php %command.full_name% test</info>

You can also output the information in other formats by using the <comment>--format</comment> option:

  <info>php %command.full_name% --format=xml</info>

It's also possible to get raw list of commands (useful for embedding command runner):

  <info>php %command.full_name% --raw</info>
EOF
            );
    }
    
    public function getNativeDefinition()
    {
        return $this->createDefinition();
    }
    
    public function createDefinition()
    {
        return new InputDefinition(array(
            new InputArgument('namespace', InputArgument::OPTIONAL, 'The namespace name'),
            new InputOption('xml', null, InputOption::VALUE_NONE, 'To output list as XML'),
            new InputOption('raw', null, InputOption::VALUE_NONE, 'To output raw command list'),
            new InputOption('format', null, InputOption::VALUE_REQUIRED, 'To output list in other formats', 'txt'),
        ));
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('xml')) {
            $input->setOption('format', 'xml');
        }
    }
    
}
