<?php

namespace TextAnalysis\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use TextAnalysis\Downloaders\NltkCorporaIndexDownloader;

/**
 * List out the possible packages to install from nltk data
 * @author dcardin
 */
class NltkPackageListCommand extends Command
{
    protected function configure()
    {
        $this->setName('pta:list')
            ->setDescription('List Corpora available in the pta data repo.')
            ->addArgument(
                'url',
                InputArgument::OPTIONAL,
                'Use a different url to download the pta/nltk package list.'
            );               
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {        
        $url = $input->getArgument('url');
        if ($url) {
            $downloader = new NltkCorporaIndexDownloader($url);     
        } else {
            $downloader = new NltkCorporaIndexDownloader();     
        }

        $packages = $downloader->getPackages();
        
        usort($packages, function($package1, $package2){ 
            return strnatcasecmp($package1->getId(), $package2->getId()); 
        });
        /** @var $package \TextAnalysis\Utilities\Nltk\Download\Package */
        $output->writeln("Packages available for installation:");
        foreach($packages as $package)
        {
            $output->writeln(" * {$package->getId()} - {$package->getName()}");
        }
    }
}
