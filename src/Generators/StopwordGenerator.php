<?php

namespace TextAnalysis\Generators;

use TextAnalysis\Analysis\FreqDist;
use TextAnalysis\Tokenizers\GeneralTokenizer;
use TextAnalysis\Filters\PunctuationFilter;
use TextAnalysis\Filters\CharFilter;
use TextAnalysis\Filters\PossessiveNounFilter;
use TextAnalysis\Documents\TokensDocument;
use TextAnalysis\Filters\LowerCaseFilter;

/**
 * A stop word generator will generate a list of stop words based on the settings
 * given by the developer. 
 * @author dcardin
 */
class StopwordGenerator
{
    const MODE_FREQ = 1;
    
    /**
     *
     * @var string[] Array of file paths of documents to be scanned
     */
    protected $filePaths = [];
    
    protected $mode = 1;
    
    /**
     *
     * @var array
     */
    protected $stopWords = [];
    
    /**
     * 
     * @param array $filePaths
     * @param int $mode
     */
    public function __construct(array $filePaths, $mode = self::MODE_FREQ) 
    {
        $this->filePaths = $filePaths;
        $this->mode = (int)$mode;
    }
    
    /**
     * Returns the array of file paths
     * @return string[]
     */
    public function getFilePaths()
    {
        return $this->filePaths;
    }
    
    /**
     * Returns an array of stop words and their frequencies
     * @return string[]
     */
    public function getStopwords()
    {
        if(!empty($this->stopWords)) {
            return $this->stopWords;
        }
                
        foreach($this->getFilePaths() as $filePath)
        {
            $content = $this->getFileContent($filePath);
            $doc = new TokensDocument((new GeneralTokenizer())
                    ->tokenize($content) );
            $doc->applyTransformation(new LowerCaseFilter())
                ->applyTransformation(new PossessiveNounFilter())
                ->applyTransformation(new PunctuationFilter())
                ->applyTransformation(new CharFilter());
            
            if($this->mode === self::MODE_FREQ) {
                $this->computeUsingFreqDist($doc->getDocumentData());
            }
            
        }
        arsort($this->stopWords);
        return $this->stopWords;                        
    }
    
    /**
     * Adds frequency counts to the stopWords property
     * @param array $tokens
     */
    protected function computeUsingFreqDist(array $tokens)
    {
        $freqDist = (new FreqDist($tokens))
            ->getKeyValuesByFrequency();
                
        foreach($freqDist as $token => $freqValue)
        {
            if(!isset($this->stopWords[$token])) {
                $this->stopWords[$token] = $freqValue;
            } else {
                $this->stopWords[$token] += $freqValue;
            }
        }        
    }
    
    /**
     * Returns the text content from the file
     * @param string $filePath
     * @return string
     */
    protected function getFileContent($filePath)
    {
        return file_get_contents($filePath);
    }
    
    public function __destruct() 
    {
        unset($this->filePaths);
        unset($this->mode);
        unset($this->stopWords);
    }    

}
