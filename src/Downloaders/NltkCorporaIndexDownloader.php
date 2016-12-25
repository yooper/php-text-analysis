<?php

namespace TextAnalysis\Downloaders;

use TextAnalysis\Utilities\Nltk\Download\Package;

/**
 * Download Corpora using the NLTK data repo
 * @author dcardin
 */
class NltkCorporaIndexDownloader 
{
    /**
     *
     * @var string The url to the index.xml file
     */
    protected $url;
    
    /**
     *
     * @var Package[]
     */
    protected $packages = [];
    
    /**
     *
     * @var boolean
     */
    protected $useCache = false;
    
    /**
     * 
     * @param string $url Default value is provided, but you can override
     * @param boolean $useCache use the cached copy if it is available, by default it is off
     */
    public function __construct($url = 'https://raw.githubusercontent.com/yooper/pta_data/gh-pages/index.xml', $useCache = false) 
    {
        $this->url = $url;
        $this->useCache = $useCache;
    }
        
    /**
     * Returns an array of packages available for download from the nltk project
     * @return array
     */
    public function getPackages()
    {
        if(empty($this->packages)) {
            
            $xml = $this->getXmlContent();
            foreach($xml->packages->package as $package)
            {
                $data = (array)$package;
                extract($data['@attributes']);
                // checksums may not exist on some remote packages
                if(!isset($checksum)) {
                    $checksum = null;
                }                
                $this->packages[] = new Package($id, $checksum, $name, $subdir, $unzip, $url);
            }            
        }
        return $this->packages;
                
    }
    
    /**
     * Get the useCache value
     * @return boolean
     */
    protected function getUseCache()
    {
        return $this->useCache;
    }
    
    /**
     * Uses file_get_contents to pull down the content from the url
     * @return SimpleXMLElement
     */
    public function getXmlContent()
    {        
        if($this->getUseCache() && file_exists(get_storage_path('cache').$this->getCacheFileName())) { 
            $contents = file_get_contents(get_storage_path('cache').$this->getCacheFileName());
        } else {
            $contents = file_get_contents( $this->getUrl());
            file_put_contents(get_storage_path('cache').$this->getCacheFileName(), $contents);
        }        
        return simplexml_load_string( $contents);
    }
    
    /**
     * 
     * @return string
     */
    protected function getCacheFileName()
    {
        return 'pta-list.xml';
    }
    
    
    /**
     * Returns the URL that file_get_contents is run against
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
        
}
