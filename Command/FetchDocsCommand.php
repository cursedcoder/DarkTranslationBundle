<?php

namespace Dark\TranslationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Finder\Finder;

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
            ->setDescription('Fetch docs from github.com, available: en, fr, it, ja, pl, ro, ru, es, tr')
            ->addArgument('language', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'available: en, fr, it, ja, pl, ro, ru, es, tr')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!function_exists('shell_exec')) {
            throw new \Exception('Function "shell_exec" is disabled, cannot work without it.');
        }

        $baseDir = $this->getContainer()->getParameter('dark_translation.source.base_dir');
        $sourceDir = $this->getContainer()->getParameter('dark_translation.source.from');

        if (!file_exists($baseDir)) {
            mkdir($baseDir, 0755, true);
        }

        $repositories = $this->getContainer()->getParameter('dark_translation.repositories');

        $langs = $input->getArgument('language');

        foreach ($langs as $lang) {
            if (isset($repositories[$lang])) {
                shell_exec(sprintf('cd %s && git clone %s', $baseDir, $repositories[$lang]));
            } else {
                throw new \Exception('Repository for ' . $lang . ' language not found.');
            }
        }

        $browser = $this->getContainer()->get('dark_translation.browser');
        $browser->dumpChanges($sourceDir);

        $output->writeln('<info>Fetching has been finished.</info>');
    }
}