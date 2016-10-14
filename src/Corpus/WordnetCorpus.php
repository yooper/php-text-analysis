<?php

namespace TextAnalysis\Corpus;

use RuntimeException;
use TextAnalysis\Models\Wordnet\Lemma;
use TextAnalysis\Models\Wordnet\Synset;
use TextAnalysis\Models\Wordnet\ExceptionMap;

/**
 * Loads the wordnet corpus for use. Borrowed heavily from nltk
 * @author yooper
 */
class WordnetCorpus extends ReadCorpusAbstract
{       
    const VERB = 'v';
    const ADJECTIVE = 'r';
    const ADVERB = 'a';
    const NOUN = 'n';
    
    /**
     *
     * @var array stores all the lex names
     */
    protected $lexNames = [];
    
    /**
     *
     * @var Lemma[]
     */
    protected $lemmas = [];
    
    /**
     * An array with indexing. Indexing is pos with offset concatenated
     * @var Synset[]
     */
    protected $synsets = [];
    
    /**
     * @var ExceptionMap[]
     */
    protected $exceptionsMap = [];
    
    
    /**
     * @var array map part of speech character to its definition
     */
    protected $posFileMaps = [
        'a' => 'adj',
        'r' => 'adv',
        'n' => 'noun',
        'v' => 'verb'
    ];
    
    /**
     * Returns the list of required files that is provided by the nltk project
     * @return string[]
     */
    public function getFileNames() 
    {
        return [
            'cntlist.rev', 'lexnames', 'index.sense','index.adj', 'index.adv', 
            'index.noun', 'index.verb','data.adj', 'data.adv', 'data.noun', 
            'data.verb', 'adj.exc', 'adv.exc', 'noun.exc', 'verb.exc'
        ];
    }
    
    /**
     * Returns array of file names with the exceptions
     * @return array
     */
    public function getExceptionFileNames()
    {
        return ['adj.exc', 'adv.exc', 'noun.exc', 'verb.exc'];
    }
    
    /**
     * 
     * @param string $line
     * @param string $pos
     * @return ExceptionMap
     */
    public function getExceptionMapFromString($line, $pos)
    {
        $tokens = explode(" ", $line);
        return new ExceptionMap($pos, $tokens[count($tokens)-1], array_slice($tokens, 0, -1));
    }
    
    /**
     * Returns the list of exception spellings
     * @return ExceptionMap[]
     * @throws RuntimeException
     */
    public function getExceptionsMap()
    {
        if(empty($this->exceptionsMap)) {
            $fileExtToPos = array_flip($this->getPosFileMaps());
            
            foreach($this->getExceptionFileNames() as $fileName )
            {
                $pos = $fileExtToPos[substr($fileName, 0, -4)];
                $fh = fopen($this->getDir().$fileName,'r');
                if(!$fh) {
                    throw new RuntimeException("wordnet file missing {$fileName}");
                }                
                                
                while($line = fgets($fh)) 
                {
                    if($line[0] === ' ') {
                        continue;
                    }
                    $this->exceptionsMap[] = $this->getExceptionMapFromString(trim($line), $pos);

                }               
                fclose($fh);
            } 
        }
        return $this->exceptionsMap;
    }
    
    
    /**
     * @return string[]
     */
    public function getLexNames()
    {
        if(empty($this->lexNames)) {
            $this->lexNames = array_map(
                    function($row) { 
                        return explode("\t", trim($row))[1];
                    },
                    file($this->getDir().'lexnames')
            );      
        }
        return $this->lexNames;
    }
    
    /**
     * 
     * @return array
     */
    public function getPosFileMaps()
    {
        return $this->posFileMaps;
    }
    
    /**
     * 
     * @return string[]
     */
    public function getDataFileNames()
    {
        return ['data.adj', 'data.adv','data.noun', 'data.verb'];
    }
    
    /**
     * 
     * @return string[]
     */
    public function getIndexFileNames()
    {
        return ['index.adj', 'index.adv','index.noun', 'index.verb'];
    }
    
    /**
     * Opens the raw data file and reads the synsets in
     * @param int $synsetOffset
     * @param string $pos Part of speech
     * @return Synset
     */
    public function getSynsetByOffsetAndPos($synsetOffset, $pos)
    {
        // check if the synset has already been cached
        if(isset($this->synsets[$pos.$synsetOffset])) {
            return $this->synsets[$pos.$synsetOffset];
        }
        
        $fileName = "data.{$this->posFileMaps[$pos]}";
        if(!in_array($fileName, $this->getDataFileNames())) {
            throw new RuntimeException("That is not a correct wordnet file {$fileName}");            
        } elseif(!file_exists($this->getDir().$fileName)) {
            throw new RuntimeException("Wordnet file missing {$fileName}");                        
        }
        
        
        $fh = fopen($this->getDir().$fileName,'r');        
        if(!$fh) {
            throw new RuntimeException("Could not open wordnet file for reading {$fileName}");                                    
        }        
        if(fseek($fh, $synsetOffset) === -1) {
            throw new RuntimeException("Could not seek to {$synsetOffset} in {$fileName}");
        }
        
        $line = trim(fgets($fh));
        fclose($fh);
        return $this->getSynsetFromString($line);
        
    }
    
    /**
     * Parse the line from the synset file and turn it into a synset object
     * @param string $line
     * @return Synset
     */
    public function getSynsetFromString($line)
    {
        $row = str_getcsv($line," ");  
        $synset = new Synset((int)$row[0], $row[2]);
        $synset->setDefinition(trim(substr($line, strpos($line,"|")+1)));
        
        for($index = 0; $index < (int)$row[3]; $index++)
        {                        
            $synset->addWord($row[4 + $index*2], $row[5 + $index*2]);
        }
        
        $startIdx =  5 + $row[3] * 2;
        $endIdx = ($row[$startIdx-1] * 4) + $startIdx; 
        $embeddedSynsets = array_splice($row, $startIdx, $endIdx);
        for($index = 0; $index < $row[$startIdx-1] * 4; $index+=4)
        {
            $linkedSynset = new Synset($embeddedSynsets[$index+1], $embeddedSynsets[$index+2]);
            $linkedSynset->setPtrSymbols([$embeddedSynsets[$index]]);
            
            // set src and target word indexes
            if((int)$embeddedSynsets[$index+3] === 0) {
                $linkedSynset->setSrcWordIdx(0);
                $linkedSynset->setTargetWordIdx(0);
            } else {
                $linkedSynset->setSrcWordIdx((int)($embeddedSynsets[$index+3]) % 100);
                $linkedSynset->setTargetWordIdx((int) floor($embeddedSynsets[$index+3] / 100));
            }   
            $synset->addLinkedSynset($linkedSynset);
        }              
        return $synset;        
    }
    
    /**
     * 
     * @param string $line
     * @return Lemma
     */
    public function getLemmaFromString($line)
    {
        $row = str_getcsv(trim($line)," ");
        return new Lemma($row[0], $row[1], (int)$row[2], (int)$row[3], array_slice($row, 4, (int)$row[3]), array_map('intval', array_slice($row, count($row)-(int)$row[2])) );        
    }
    
    /**
     * @return Lemma[] Returns an array of lemmas
     * @throws RuntimeException
     */
    public function getLemmas()
    {
        if(empty($this->lemmas)) { 
            foreach($this->getIndexFileNames() as $fileName )
            {
                $seenBefore = [];
                $fh = fopen($this->getDir().$fileName,'r');
                if(!$fh) {
                    throw new RuntimeException("wordnet file missing {$fileName}");
                }                
                while($line = fgets($fh)) 
                {
                    if($line[0] === ' ' || isset($seenBefore[md5(trim($line))] )) {
                        continue;
                    }
                    $seenBefore[md5(trim($line))] = 1;
                    $this->lemmas[] = $this->getLemmaFromString($line);

                }               
                fclose($fh);
            }
        }
        return $this->lemmas;        
    }
}
