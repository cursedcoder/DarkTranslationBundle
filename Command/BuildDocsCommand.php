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

        $dirs = array('sensio', 'symfony', 'sensio/sphinx');
        $files = array(
            'conf.py', 'sensio/__init__.py', 'sensio/sphinx/__init__.py',
            'sensio/sphinx/configurationblock.py', 'sensio/sphinx/phpcode.py',
            'sensio/sphinx/php.py', 'sensio/sphinx/refinclude.py',
            'symfony/theme.conf', '/symfony/layout.html'
        );

        foreach ($dirs as $dir) {
            mkdir($sourcePath . '/' . $dir);
        }
        foreach ($files as $file) {
            copy($configPath . '/' . $file, $sourcePath . '/' . $file);
        }

        shell_exec(sprintf('sphinx-build -b html %s %s', $sourcePath, $buildPath));

        $files = array_merge($files, array(
            'sensio/__init__.pyc',
            'sensio/sphinx/__init__.pyc',
            'sensio/sphinx/configurationblock.pyc',
            'sensio/sphinx/phpcode.pyc',
            'sensio/sphinx/refinclude.pyc'
        ));

        $dirs = array_reverse($dirs);

        foreach ($files as $file) {
            unlink($sourcePath . '/' . $file);
        }
        foreach ($dirs as $dir) {
            rmdir($sourcePath . '/' . $dir);
        }

        $output->writeln('<info>Building has been finished.</info>');
    }
}