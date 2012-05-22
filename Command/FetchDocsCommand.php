<?php

namespace Dark\TranslationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Dumper;

/**
 * This command fetches documentation repos from github
 *
 * @author Evgeniy Guseletov <d46k16@gmail.com>
 */
class FetchDocsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('dark-translation:fetch-docs')
            ->setDescription('Fetch docs from github.com, available: en, fr, it, ja, pl, ro, ru, es')
            ->addArgument('language', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'available: en, fr, it, ja, pl, ro, ru, es')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!function_exists('shell_exec')) {
            throw new \Exception('Function "shell_exec" is disabled, cannot work without it.');
        }

        $check = shell_exec("sphinx-build");

        if (!strstr($check, "Sphinx v1")) {
            throw new \Exception(
                'Sphinx not found or installed version is too old.
                 You can install it running "easy_install -U sphinx sphinxcontrib-phpdomain"'
            );
        }

        $baseDir = $this->getContainer()->getParameter('dark_translation.source.base_dir');

        if (!file_exists($baseDir)) {
            mkdir($baseDir, 0755, true);
        }

        $repositories = $this->getContainer()->getParameter('dark_translation.repositories');

        $langs = $input->getArgument('language');

        foreach ($langs as $lang) {
            if (isset($repositories[$lang])) {
                shell_exec(sprintf('cd %s && git clone %s', $baseDir, $repositories[$lang]));
            } else {
                throw new \Exception('Not found repository for ' . $lang . ' language.');
            }
        }

        $output->writeln('<info>Fetching is finished.</info>');
    }
}