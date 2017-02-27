<?php
namespace TextAnalysis\Downloaders;

use TextAnalysis\Utilities\Nltk\Download\Package;
use Exception;
use ZipArchive;

/**
 * Download data packages from the nltk data repo and install them
 * into a local directory
 * @author yooper
 */
class DownloadPackageFactory 
{
    /**
     *
     * @var Package 
     */
    protected $package;
    
    /**
     * Installs the package 
     * @param Package $package
     */
    protected function __construct(Package $package) 
    {
        $this->package = $package;
        $this->initialize();
        
        // latest and greatest package was already downloaded
        if(file_exists($this->getDownloadFullPath()) && $this->verifyChecksum()) {
            return;
        }     
               
    }
    
    /**
     * Initializes and downloads the remote corpus
     * @param Package $package
     * @return \TextAnalysis\Downloaders\DownloadPackageFactory
     */
    static public function download(Package $package)
    {
        return new DownloadPackageFactory($package);  
    }
    
    /**
     * Verify the packages checksum against the downloaded file
     * if the package has a checksum
     * @return boolean
     */
    public function verifyChecksum()
    {
        if(empty($this->getPackage()->getChecksum())) {
            return true;
        }
        return $this->getPackage()->getChecksum() === md5_file($this->getDownloadFullPath());
    }
    
    /**
     * de-compress the downloaded corpus into the install directory, or
     * copy the files into the install directory
     */
    public function unpackPackage()
    {
        // it is zipped, we must unzip it
        if($this->getPackage()->getUnzip()) {
            $this->extractZip($this->getDownloadFullPath(), $this->getInstallDir());
        } else {
            $this->recursiveCopy($this->getDownloadFullPath(), $this->getInstallDir());                        
        }        
    }
    
    
    
    /**
     * Recursive copy the directory
     * @param string $src
     * @param string $dst
     */
    protected function recursiveCopy($src,$dst)
    {
        if($this->isZip($src)) { 
            $this->extractZip($src, $this->getInstallDir());
            return;
        }
        $dir = opendir($src); 
        if(!is_dir($dst)) {
            mkdir($dst);
        }
        while(false !== ( $file = readdir($dir)) ) { 
            if (( $file != '.' ) && ( $file != '..' )) { 
                if ( is_dir($src . '/' . $file) ) { 
                    recursiveCopy($src . '/' . $file,$dst . '/' . $file); 
                } 
                else { 
                    copy($src . '/' . $file,$dst . '/' . $file); 
                } 
            } 
        } 
        closedir($dir); 
    }
    
    /**
     * 
     * @param string $path
     * @return boolean
     */
    protected function isZip($path)
    {
        $r = zip_open($path);
        if(is_resource($r)) { 
            zip_close($r);
            return true;
        }
        return false;
    }
    
    /**
     * Use PHP's ZipArchive to extract out the data
     */
    protected function extractZip($srcPath, $extractToDir)
    {
        $zip = new ZipArchive();
        $r = $zip->open($srcPath);
        if($r) {
            $zip->extractTo($extractToDir);
            $zip->close();             
        }        
    }
    
    /**
     * Initialize the directories required for the download
     */
    public function initialize()
    {
        if(!is_dir(dirname($this->getDownloadFullPath()))) {
            mkdir(dirname($this->getDownloadFullPath()), 0755, true);
        }
        
        if(!is_dir($this->getInstallDir())) {
            mkdir($this->getInstallDir(), 0755, true);
        }                        
        
    }
    
    
    /**
     * Has the full path to where the download should go
     * @return string
     */
    public function getDownloadFullPath()
    {
       return  sys_get_temp_dir().DIRECTORY_SEPARATOR.'pta-downloads'
               .DIRECTORY_SEPARATOR.$this->getPackage()->getSubdir()
               .DIRECTORY_SEPARATOR.basename($this->getPackage()->getUrl());
    }
    
    
    /**
     * The path where the software should be installed
     * @return string
     */
    public function getInstallDir()
    {
        return 'storage'.DIRECTORY_SEPARATOR.$this->getPackage()->getSubdir().DIRECTORY_SEPARATOR;
    }
    
    /**
     * 
     * @return Package
     */
    public function getPackage()
    {
        return $this->package;
    }
}
