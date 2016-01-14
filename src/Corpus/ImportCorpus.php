<?php

namespace TextAnalysis\Corpus;

use TextAnalysis\Utilities\Nltk\Download\Package;
use TextAnalysis\Downloaders\NltkCorporaIndexDownloader;
use TextAnalysis\Tokenizers\GeneralTokenizer;
use TextAnalysis\Tokenizers\SentenceTokenizer;
use TextAnalysis\Tokenizers\LambdaTokenizer;

/**
 * Provides the main access point into the nltk corpora
 * @author dcardin
 */
class ImportCorpus 
{   
    /**
     *
     * @var Package The selected package
     */
    protected $package;
    
    /**
     * Returns an array of files that were installed into the packages directory
     * @return array
     */
    public function getFileIds()
    {
        $installationPath = $this->getPackage()->getInstallationPath();
        // use array values to start the indexing of the array @ zero
        return array_values(array_diff(scandir($installationPath), array('..', '.')));        
    }
    
    /**
     * The id of the package to load
     * @var string
     */
    protected $packageId;
    
    /**
     * Return an array of tokenized words
     * @param string|null $fileId
     * @param \TextAnalysis\Tokenizers\TokenizerAbstract
     * @return array
     */
    public function getWords($fileId = null, $tokenizer = null)
    {
        if(!$tokenizer) { 
            $tokenizer = new GeneralTokenizer();
        }
        $fileIds = [];
        if(empty($fileId)) { 
            $fileIds = $this->getFileIds();
        } else {
            $fileIds = [$fileId];
        }
        
        $words = [];
        foreach($fileIds as $filename )
        {
            $content = file_get_contents($this->getPackage()->getInstallationPath().$filename);
            $words = array_merge($words, $tokenizer->tokenize($content));
            unset($content);
        }
        return $words;
    }

    /**
     * Return an array of tokenized sentences, see getWords
     * @param string|null $fileId
     * @return array
     */    
    public function getSentences($fileId = null)
    {
        return $this->getWords($fileId, new SentenceTokenizer());
    }
    
    /**
     * Each array element is the text of the selected file loaded file, see getWords
     * @param  $fileId
     * @return array of strings
     */
    public function getRaw($fileId = null)
    {
        // does nothing with the text
        $lamdaFunction = function($text){ 
            return [$text];
        };        
        return $this->getWords($fileId, new LambdaTokenizer($lamdaFunction));        
    }
    
    
    /**
     * Provide the package id
     * @param string $packageId
     */
    public function __construct($packageId) 
    {
        $this->packageId = $packageId;
    }
        
    /**
     * 
     * @return Package
     */
    public function getPackage()
    {
        if(empty($this->package)) {
            // loads the package list from cache
            $packages = (new NltkCorporaIndexDownloader(null, true))->getPackages();

            $filteredPackages = array_filter($packages, function($package) use ($packageId){
                return ($package->getId() == $packageId);
            });

            $this->package = array_values($filteredPackages)[0];                   
        }        
        return $this->package;
    }
}
