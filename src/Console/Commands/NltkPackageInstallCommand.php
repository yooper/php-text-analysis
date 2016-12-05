<?php

namespace TextAnalysis\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;

use TextAnalysis\Downloaders\DownloadPackageFactory as DPF;
use TextAnalysis\Downloaders\NltkCorporaIndexDownloader;



/**
 * Installs the selected nltk corpus package 
 *
 * @author yooper
 */
class NltkPackageInstallCommand extends Command
{
    /**
    * @var ProgressBar
    */
    protected $progressBar = null;
    
    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    private $output;

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
        $this->output = $output;
        $packageId = $input->getArgument('package');
        
        $listPackages = (new NltkCorporaIndexDownloader())->getPackages();

        $packageFound = null;
        
        foreach($listPackages as $package) 
        { 
            if($packageId == $package->getId()) {
                $packageFound = $package;
                break;
            }
        }
        
        if(!$packageFound) { 
            $output->writeln("Package {$packageId} was not found, try textconsole pta:list, to see the available packages");
        } else {
            
            $download = DPF::download($package);  
            // Create stream context.
            $context = stream_context_create([], ['notification' => [$this, 'progress']]);

            // Pipe file.
            $resource = fopen($packageFound->getUrl(), 'r', null, $context);
            $stream = fopen($download->getDownloadFullPath(), 'w+');
            if (!$stream) {
                $output->writeln("Package {$packageFound->getId()} - {$packageFound->getName()} install failed, permission denied to create file into {$download->getDownloadFullPath()}");
            }

            stream_copy_to_stream($resource, $stream);
            
            if (!fclose($stream)) {
                $output->writeln("Could not save file {$download->getDownloadFullPath()}");
            }

            // End output.
            $this->progressBar->finish();

            if(!$download->verifyChecksum()) {
                $output->writeln("Bad checksum for the downloaded package {$packageFound->getId()}");
                exit;        
            }
            $download->unpackPackage();
            $output->writeln(PHP_EOL);
            $output->writeln("Package {$package->getId()} - {$package->getName()} was installed into {$download->getInstallDir()}");
        }        
    }

    /**
     * @param int $notificationCode
     * @param int $severity
     * @param string $message
     * @param int $messageCode
     * @param int $bytesTransferred
     * @param int $bytesMax
     */
    public function progress($notificationCode, $severity, $message, $messageCode, $bytesTransferred, $bytesMax)
    {
        if (STREAM_NOTIFY_REDIRECTED === $notificationCode) {
            $this->progressBar->clear();
            $this->progressBar = null;
            return;
        }

        if (STREAM_NOTIFY_FILE_SIZE_IS === $notificationCode) {
            if ($this->progressBar) {
                $this->progressBar->clear();
            }
            $this->progressBar = new ProgressBar($this->output, $bytesMax);
        }

        if (STREAM_NOTIFY_PROGRESS === $notificationCode) {
            if (is_null($this->progressBar)) {
                $this->progressBar = new ProgressBar($this->output);
            }
            $this->progressBar->setProgress($bytesTransferred);
        }

        if (STREAM_NOTIFY_COMPLETED === $notificationCode) {
            $this->finish($bytesTransferred);
        }
    }

}
