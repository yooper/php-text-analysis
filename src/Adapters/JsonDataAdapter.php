<?php
namespace TextAnalysis\Adapters;
use TextAnalysis\Interfaces\IDataReader;
/**
 * A simple wrapper adapter class around json_decode and json decode
 * @author yooper
 */
class JsonDataAdapter implements IDataReader
{
    /**
     * Json encoded string
     * @var string 
     */
    protected $jsonStr;
    
    /**
     *
     * @var boolean 
     */
    protected $assoc = true;
    
    /**
     *
     * @param string $jsonStr
     * @param boolean $assoc 
     */
    public function __construct($jsonStr, $assoc = true)
    {
        $this->jsonStr = $jsonStr;
        $this->assoc = $assoc;
    }
    
    /**
     * Returns the json data as an array
     * @return array 
     */
    public function read() 
    {
        return json_decode($this->jsonStr, $this->assoc);
    }
}
