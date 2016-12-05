<?php

namespace TextAnalysis\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use TextAnalysis\Downloaders\DownloadPackageFactory as DPF;
use TextAnalysis\Downloaders\NltkCorporaIndexDownloader;


/**
 * Installs the selected nltk corpus package 
 *
 * @author yooper
 */
class NltkPackageInstallCommand extends Command
{
    protected function configure()
    {
        $this->setName('pta:install:package')
            ->setDescription('Install the selected corpus')
            ->addArgument(
                'package',
                InputArgument::REQUIRED,
                'You must selected a valid package id, use pta:list to explore the available options.'
            );               
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {        
        $packageId = $input->getArgument('package');
        
        $listPackages = (new NltkCorporaIndexDownloader())->getPackages();

        $packageFound = false;
        
        foreach($listPackages as $package) 
        { 
            if($packageId == $package->getId()) {
                $packageFound = true;
                $download = DPF::download($package);                
                break;
            }
        }
        
        if(!$packageFound) { 
            $output->writeln("Package {$packageId} was not found, try textconsole pta:list, to see the available packages");
        } else {
            $output->writeln("Package {$package->getId()} - {$package->getName()} was installed into {$download->getInstallDir()}");
        }        
    }
}
