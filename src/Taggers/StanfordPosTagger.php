<?php


namespace TextAnalysis\Taggers;

use TextAnalysis\Filters\PunctuationFilter;
use TextAnalysis\Tokenizers\WhitespaceTokenizer;

/**
 * Call Stanford's POS Tagger
 * @author yooper
 */
class StanfordPosTagger extends StanfordAbstract
{
    public function __construct($jarPath, $classifierPath, $javaOptions = array(), $separator = '/') {
        parent::__construct($jarPath, $classifierPath, $javaOptions, $separator);
        // created the temp file
        $this->tmpFilePath = tempnam(sys_get_temp_dir(), "stanford_pos_");
    }   
    
    /**
     * 
     * @return string
     */
    public function getCommand() 
    {
        return escapeshellcmd(
            $this->getPathToJava() .
            " ".implode(" ", $this->getJavaOptions()) .
            " -cp " . $this->getJarPath() . $this->getPathSeparator() .                 
            dirname($this->getJarPath()).DIRECTORY_SEPARATOR."lib".DIRECTORY_SEPARATOR."*".
            " edu.stanford.nlp.tagger.maxent.MaxentTagger " . 
            " -model {$this->getClassifierPath()}" .
            " -textFile {$this->getTmpFilePath()}" .
            " -outputFormat tsv"
        );        
    }

    protected function getParsedOutput() 
    {
        $data = [];   
                
        $lines = explode(PHP_EOL, $this->output);
        foreach($lines as $line)
        {
            $line = str_replace("\t", $this->getSeparator(), $line);
            $row = array_map('trim', explode($this->getSeparator(), $line));
            
            if(empty($row[0]) || empty(end($row)) ) {
                continue;
            }
            $len = count($row);
            for($index = 0; $index < $len-1; $index++)
            {
                $data[] = [$row[$index],$row[$len-1]];
            }
        }

        return $data;        
        
    }

}
