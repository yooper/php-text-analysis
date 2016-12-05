<?php

namespace TextAnalysis\Taggers;

use RuntimeException;
use TextAnalysis\Tokenizers\WhitespaceTokenizer;
use TextAnalysis\Filters\PunctuationFilter;

/**
 * Gives access to the Stanford NER Tagger
 * @author yooper
 */
class StanfordNerTagger extends StanfordAbstract
{
    public function __construct($jarPath = null, $classifierPath = null, $javaOptions = array(), $separator = '/') {
        $nerPath = 'taggers/stanford-ner-2015-12-09';
        
        if(!$jarPath) {
            $jarPath = get_storage_path($nerPath).'stanford-ner.jar';
        }

        if(!$classifierPath) {
            $classifierPath = get_storage_path($nerPath.DIRECTORY_SEPARATOR."classifiers")."english.all.3class.distsim.crf.ser.gz";
        }

        parent::__construct($jarPath, $classifierPath, $javaOptions, $separator);
        // created the temp file
        $this->tmpFilePath = tempnam(sys_get_temp_dir(), "stanford_ner_");
    }

    public function getCommand() 
    {
        return escapeshellcmd(
            $this->getPathToJava() .
            " ".implode(" ", $this->getJavaOptions()) .
            " -cp " . $this->getJarPath() . $this->getPathSeparator() .                 
            dirname($this->getJarPath()).DIRECTORY_SEPARATOR."lib".DIRECTORY_SEPARATOR."*".
            " edu.stanford.nlp.ie.crf.CRFClassifier " . 
            " -loadClassifier {$this->getClassifierPath()}" .
            " -textFile {$this->getTmpFilePath()}"   
        );        
    }

    /**
     * 
     * @return array
     */
    protected function getParsedOutput() 
    {
        $data = [];   
        
        $filter = new PunctuationFilter();
        $phrases = (new WhitespaceTokenizer())->tokenize($this->output);        
        foreach($phrases as $phrase)
        {
            $tokens = explode("{$this->getSeparator()}", $phrase);
            $type = array_pop($tokens);
    
            foreach($tokens as $token) 
            {
                if(empty($token) || empty($filter->transform($token))) {
                    continue;
                }
                $data[] = [$token,$type];         
            }            
        }
        return $data;        
    }

}
