<?php
namespace TextAnalysis\Adapters;
use TextAnalysis\Interfaces\IDataReader;
/**
 * wraps an array
 * @author yooper
 */
class ArrayDataReaderAdapter implements IDataReader
{
    /**
     *
     * @var array 
     */
    protected $data = null;
    
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    
    /**
     *
     * @return array 
     */
    public function read()
    {
        return $this->data;
    }
}

