<?php
namespace TextAnalysis\Adapters;
use TextAnalysis\Interfaces\IDataReader;
/**
 *
 * @author yooper
 */
class JsonDataAdapter implements IDataReader
{
    /**
     * Json encoded string
     * @var string 
     */
    protected $jsonStr;
    
    public function __construct($jsonStr)
    {
        $this->jsonStr = $jsonStr;
    }
    
    /**
     * Returns the json data as an array
     * @return array 
     */
    public function read() 
    {
        return json_decode($this->jsonStr, true);
    }
}
