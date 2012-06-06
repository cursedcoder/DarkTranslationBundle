<?php

namespace Dark\TranslationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * This command builds html documentation using sphinx
 *
 * @author Evgeniy Guseletov <d46k16@gmail.com>
 */
class BuildDocsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('dark-translation:build-docs')
            ->setDescription('Build your sphinx docs')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!function_exists('shell_exec')) {
            throw new \Exception('Function "shell_exec" is disabled, cannot work without it.');
        }

        $sourcePath = $this->getContainer()->getParameter('dark_translation.source.to');
        $buildPath = $this->getContainer()->getParameter('dark_translation.build.path');

        if (!file_exists($sourcePath)) {
            throw new \Exception('Folder ' . $sourcePath . ' does not exist.');
        }
        if (!file_exists($buildPath)) {
            mkdir($buildPath, 0755, true);
        }

        $configPath = __DIR__.'/../Resources/python';

        shell_exec(sprintf('sphinx-build -c %s -b html %s %s', $configPath, $sourcePath, $buildPath));

        $output->writeln('<info>Building has been finished.</info>');
    }
}