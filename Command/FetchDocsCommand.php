<?php

namespace Dark\TranslationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Dumper;

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

    /**
     * @todo need solution for fecthing git
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!function_exists('shell_exec')) {
            throw new \Exception('Function "shell_exec" is disabled, cannot work without it.');
        }

        $docsDir = $this->getContainer()->getParameter('dark_translation.build.path');

        if (!file_exists($docsDir)) {
            mkdir($docsDir, 0755, true);
        }

        $repositories = $this->getContainer()->getParameter('dark_translation.repositories');
        $langs = $input->getArgument('language');

        foreach ($langs as $lang) {
            if (isset($repositories[$lang])) {
                shell_exec(sprintf('cd %s && git clone %s', $docsDir, $repositories[$lang]));
            } else {
                throw new \Exception('Not found repository for ' . $lang . ' language.');
            }
        }

        $output->writeln('<info>Fetching is finished.</info>');
    }
}