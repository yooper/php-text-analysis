<?php
namespace TextAnalysis\Adapters;
use TextAnalysis\Interfaces\IDataReader;
/**
 *
 * @author yooper
 */
class JsonLookupDataAdapter implements IDataReader
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
     * Returns a lookup table
     * @return array 
     */
    public function read() 
    {
        return json_decode($this->jsonStr, true);
    }
}
