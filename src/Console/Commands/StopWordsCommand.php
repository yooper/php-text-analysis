<?php

namespace TextAnalysis\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use TextAnalysis\Generators\StopwordGenerator;

/**
 * Takes a directory of files or a single file and outputs the results for the
 * stop words generator to the command line. 
 * 
 *
 * @author dcardin
 */
class StopWordsCommand extends Command
{
    protected function configure()
    {
        $this->setName('stopwords:generate ')
            ->setDescription('Process a document or corpus of stop words, echos to command line')
            ->addArgument(
                'path',
                InputArgument::REQUIRED,
                'Path to a file or directory to read in. MUST be text files'
            )
            ->addArgument(
                'type', 
                InputArgument::OPTIONAL, 
                "type can be json or csv", 'json'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {                
        $path = $input->getArgument('path');
        
        if(!file_exists($path)) {
            $output->writeln("{$path} is not a file or a path");            
        }
        
        $filePaths = [];
        if(is_file($path)) {
            $filePaths = [realpath($path)];
        } elseif(is_dir($path)) { 
            $filePaths = array_diff(scandir($path), array('..', '.'));
        } else {
            $output->writeln("{$path} is not known.");  
        }
        
        $generator = new StopwordGenerator($filePaths);        
        if($input->getArgument('type') === 'json') {
            echo json_encode($this->toArray($generator->getStopwords()), JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE);
            echo json_last_error_msg();
            die;
            $output->write(json_encode($this->toArray($generator->getStopwords())));
        } else {
            $stopwords = $generator->getStopwords();
            $stdout = fopen('php://stdout', 'w');
            echo 'token,freq'.PHP_EOL;
            foreach($stopwords as $token => $freq)
            {
                fputcsv($stdout, [utf8_encode($token), $freq]).PHP_EOL;
            }
            fclose($stdout);
        }
        return 0;
    }
    
    /**
     * So you can easily serialize the data to json
     * @return array
     */
    protected function toArray(array $stopWords) 
    {
        $data = [];
        foreach($stopWords as $key => $value)
        {
            $data[] = ['token' => utf8_encode($key), 'freq' => $value];
        }
        return $data;
    }    
}
