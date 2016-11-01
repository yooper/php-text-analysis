<?php

namespace TextAnalysis\Utilities\Nltk\Download;

/**
 * Tracks download packages from nltk data repo
 * @author yooper
 */
class Package 
{

    /**
     * @var string
     */
    protected $checksum;    
    protected $id;    
    protected $name;    
    protected $subdir;            
    protected $unzip;        
    protected $url;  
          
    /**
     * 
     * @param string $id
     * @param string $checksum
     * @param string $name
     * @param string $subdir
     * @param string $unzip
     * @param string $url
     */
    public function __construct($id, $checksum, $name, $subdir, $unzip, $url) 
    {
        $this->id = $id;
        $this->checksum = $checksum;
        $this->name = $name;
        $this->subdir = $subdir;
        $this->unzip = $unzip;
        $this->url = $url;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getChecksum()
    {
        return $this->checksum;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getSubdir()
    {
        return $this->subdir;
    }
    
    public function getUnzip()
    {
        return $this->unzip;
    }
    
    public function getUrl()
    {
        return $this->url;
    }   
    
    /**
     * Returns the path the package should be installed into
     * @return string
     */
    public function getInstallationPath()
    {
        return get_storage_path($this->getSubdir().DIRECTORY_SEPARATOR.$this->getId());
    }
}
