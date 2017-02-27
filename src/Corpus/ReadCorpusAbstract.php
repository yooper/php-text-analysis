<?php

namespace TextAnalysis\Corpus;

/**
 * Abstract class for making corpus readers
 */
abstract class ReadCorpusAbstract 
{
    /**
     *
     * @var string the directory the corpus files are located
     */
    protected $dir;
    
    /**
     *
     * @var string which language to use, default is eng
     */
    protected $lang = 'eng';
    
    
    /**
     * 
     * @param string $dir the directory the corpus files are located
     * @param string $lang language to use, default is eng
     */
    public function __construct($dir, $lang = 'eng') 
    {
        $this->dir = $dir;
        $this->lang = $lang;               
    }
    
    /**
     * 
     * @return string language to use, default is eng
     */
    public function getLanguage()
    {
        return $this->lang;
    }
    
    /**
     * @return  string the directory the corpus files are located
     */
    public function getDir()
    {
        return $this->dir;
    }
    
    
    
    /**
     * @return string[] Return the list of file names that must be loaded to use the corpus
     * Should use relative paths
     */
    abstract public function getFileNames();
    
    
}


