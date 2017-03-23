<?php

namespace TextAnalysis\Corpus;

use PDO;
use Yooper\Nicknames;

/**
 * Opens the US names sqlite database 
 *
 * @author yooper
 */
class NameCorpus extends ReadCorpusAbstract
{
    /**
     *
     * @var PDO
     */
    protected $pdo;
    
    /**
     *
     * @var Nicknames
     */
    protected $nickNames;
    
    /**
     *
     * @var array
     */
    protected $firstNameCache = [];
    
    /**
     *
     * @var array
     */
    protected $lastNameCache = [];
    
    public function __construct($dir = null, $lang = 'eng') 
    {
        $this->nickNames = new Nicknames();
        
        if(!$dir) { 
            $dir = get_storage_path('corpora');
        }
        parent::__construct($dir, $lang);
    }
    
    public function getNickNameExact($name) : string
    {
        return $this->nickNames->query($name);
    }
    
    public function getNickNameFuzzy($name) : array
    {
        return $this->nickNames->fuzzy($name);
    }
    
    public function getFileNames(): array
    {
        return ['us_names.sqlite3'];
    }
    
    /**
     * 
     * @param string $name
     * @return boolean
     */
    public function isFirstName($name) : bool
    {
        return !empty($this->getFirstName($name));
    }
    
    /**
     * @todo make this more flexible
     * @param string $name
     * @return array
     */
    public function getFirstName($name) : array
    {
        if(!isset($this->firstNameCache[$name])) {
            $stmt = $this->getPdo()->prepare("SELECT * FROM us_names_by_year WHERE name = LOWER(:name) LIMIT 1"); 
            $stmt->bindParam(':name', $name);
            $stmt->execute();
            $this->firstNameCache[$name] = $stmt->fetchAll(PDO::FETCH_ASSOC) ?? [];  
        }
        return $this->firstNameCache[$name];
    }
    
    /**
    * 
    * @param string $name
    * @return boolean
    */    
    public function isLastName($name) : bool
    {
        return !empty($this->getLastName($name));
    }
    
    /**
     * 
     * @param string $name
     * @return array
     */
    public function getLastName($name) : array
    {
        if(!isset($this->lastNameCache[$name])) {        
            $stmt = $this->getPdo()->prepare("SELECT * FROM surnames WHERE name = LOWER(:name)"); 
            $stmt->bindParam(':name', $name);
            $stmt->execute();
            $r = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->lastNameCache[$name] =  (!$r) ? [] : $r;            
        }
        return $this->lastNameCache[$name];
    }
    
    /**
     * 
     * @param string $name
     * @return bool
     */
    public function isFullName($name) : bool
    {
        $tokens = explode(" ", $name);
        if(count($tokens) < 2) { 
            return false;
        }
        return !empty($this->isFirstName(current($tokens))) && !empty($this->isLastName(end($tokens)));
    }   
    
    
    
    /**
     * Return the raw pdo
     * @return PDO
     */
    public function getPdo() : PDO
    {
        if(empty($this->pdo)) {
            $this->pdo = new PDO("sqlite:".$this->getDir().$this->getFileNames()[0]);
        }
        return $this->pdo;
    }

    public function __destruct() 
    {
        unset($this->pdo);
        unset($this->firstNameCache);
        unset($this->lastNameCache);
        unset($this->nickNames);
    }
    
}

    
