<?php

namespace TextAnalysis\Corpus;

use PDO;

/**
 * Opens the US names sqlite database 
 *
 * @author yooper
 */
class NameCorpus extends ReadCorpusAbstract
{
    protected $pdo;
    
    public function __construct($dir = null, $lang = 'eng') 
    {
        if(!$dir) { 
            $dir = get_storage_path('corpora');
        }
        parent::__construct($dir, $lang);
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
        return $this->isName('names_by_state_and_year', $name);
    }
    
    /**
    * 
    * @param string $name
    * @return boolean
    */    
    public function isLastName($name) : bool
    {
        return $this->isName('surnames', $name);
    }
    
    /**
     * Check if the name exists
     * @param string $tableName
     * @param string $name
     * @return boolean
     */
    protected function isName($tableName, $name) : bool
    {
        $stmt = $this->getPdo()->prepare("SELECT name FROM $tableName WHERE name = LOWER(:name) LIMIT 1"); 
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        return !empty($stmt->fetchColumn());         
    }
    
    
    /**
     * @return PDO
     */
    public function getPdo() : PDO
    {
        if(empty($this->pdo)) {
            $this->pdo = new PDO("sqlite:".$this->getDir().$this->getFileNames()[0]);
        }
        return $this->pdo;
    }    
}

    
