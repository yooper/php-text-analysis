<?php

namespace TextAnalysis\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use TextAnalysis\Downloaders\DownloadPackageFactory as DPF;
use TextAnalysis\Downloaders\NltkCorporaIndexDownloader;


/**
 * Install all the pta data packages
 * @author yooper
 */
class NltkPackageInstallAllCommand extends Command
{
    protected function configure()
    {
        $this->setName('pta:install:all')
            ->setDescription('Install all packages from pta data');              
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {                
        $listPackages = (new NltkCorporaIndexDownloader())->getPackages();

        // create a new progress bar (50 units)
        $progress = new ProgressBar($output, count($listPackages));
        $progress->setFormat(' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s%');

        // start and displays the progress bar
        $progress->start();

        foreach($listPackages as $package)
        {
            // ... do some work
            $progress->setMessage("Downloading {$package->getName()}");
            
            $download = DPF::download($package);                
            $progress->setMessage("Package {$package->getId()} - {$package->getName()} was installed into {$download->getInstallDir()}");
            // advance the progress bar 1 unit
            $progress->advance();

        }

        // ensure that the progress bar is at 100%
        $progress->finish();        
    }
                  
}
