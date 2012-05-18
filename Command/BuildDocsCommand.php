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

        $sourcePath = $this->getContainer()->getParameter('dark_translation.source.to');
        $buildPath = $this->getContainer()->getParameter('dark_translation.build.path');

        if (!file_exists($sourcePath)) {
            throw new \Exception('Folder ' . $sourcePath . ' is not exist.');
        }
        if (!file_exists($buildPath)) {
            mkdir($buildPath, 0755, true);
        }

        $pyConfig = __DIR__.'/../Resources/python/*';

        shell_exec(sprintf('cp -R %s %s', $pyConfig, $sourcePath));
        shell_exec(sprintf('rm -rf %s', $buildPath));
        shell_exec(sprintf('sphinx-build -b html %s %s', $sourcePath, $buildPath));

        shell_exec(sprintf('unlink %s/conf.py', $sourcePath));
        shell_exec(sprintf('rm -rf %s/sensio', $sourcePath));
        shell_exec(sprintf('rm -rf %s/symfony', $sourcePath));

        $output->writeln('<info>Building is finished.</info>');
    }
}