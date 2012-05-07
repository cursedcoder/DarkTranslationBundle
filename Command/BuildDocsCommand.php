<?php

namespace Dark\TranslationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BuildDocsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('dark-translation:build-docs')
            ->setDescription('Build your sphinx docs')
        ;
    }

    /**
     * @todo need some improving, it has heavy depends on shell_exec and *nix systems
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!function_exists('shell_exec')) {
            throw new \Exception('Function "shell_exec" is disabled, cannot work without it.');
        }

        $path = $this->getContainer()->getParameter('dark_translation.build.path');

        if (!file_exists($path)) {
            throw new \Exception('Folder ' . $path . ' is not exist.');
        }

        $pyConfig = __DIR__.'/../Resources/python/*';

        shell_exec(sprintf('cp -R %s %s', $pyConfig, $path));
        shell_exec(sprintf('rm -rf %s', $path . '/../build'));
        shell_exec(sprintf('sphinx-build -b html %s %s', $path, $path . '/../build'));

        shell_exec(sprintf('unlink %s/conf.py', $path));
        shell_exec(sprintf('unlink %s/configurationblock.py', $path));
        shell_exec(sprintf('unlink %s/configurationblock.pyc', $path));

        $output->writeln('<info>Building is finished.</info>');
    }
}