<?php

namespace TextAnalysis\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TextAnalysis\Downloaders\NltkCorporaIndexDownloader;
use Symfony\Component\Console\Input\ArrayInput;


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

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {                
        $listPackages = (new NltkCorporaIndexDownloader())->getPackages();

        foreach($listPackages as $package)
        {
            $command = $this->getApplication()->find('pta:install:package');
            $args = [
                'command' => 'pta:install:package',
                'package' => $package->getId()
            ];

            $packageInstallerInput = new ArrayInput($args);
            $command->run($packageInstallerInput, $output);
        }

        return 0;
    }
                  
}
