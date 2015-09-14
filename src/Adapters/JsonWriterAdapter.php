<?php
namespace TextAnalysis\Adapters;
use TextAnalysis\Interfaces\IDataWriter;

/**
 * return a json string based on the provided string
 * this class is a wrapper around json_encode
 * @author yooper
 */
class JsonWriterAdapter implements IDataWriter
{
    /**
     * Internal data structure
     * @var array 
     */
    protected $data = array();
    
    /**
     *
     * @var int
     */
    protected $options = 0;
    
    /**
     *
     * @var int 
     */
    protected $depth = 512;
    
    /**
     *
     * @param array $data
     * @param int $options
     * @param int $depth 
     */
    public function __construct(array &$data, $options = 0, $depth = 512)
    {
        $this->data = $data;
        $this->options = $options;
        $this->depth = $depth;
    }
    
    /**
     * return a json encoded string
     * @return string 
     */
    public function write()
    {
        return json_encode($this->data, $this->options, $this->depth);
    }
}

