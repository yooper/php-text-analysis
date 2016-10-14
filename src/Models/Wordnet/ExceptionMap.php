<?php

namespace TextAnalysis\Models\Wordnet;

/**
 * Maps the exception file data into an object
 * @author dcardin
 */
class ExceptionMap 
{
    /**
     *
     * @var string pos 
     */
    protected $pos = null;
    
    /**
     *
     * @var string[] String of exception words to look for
     */
    protected $exceptionList = [];
    
    /**
     *
     * @var string The target word the strings in the exceptionList get mapped to
     */
    protected $target = null;
    
    /**
     * 
     * @param string $pos
     * @param string $target
     * @param array $exceptionList
     */
    public function __construct($pos, $target, array $exceptionList) 
    {
        $this->pos = $pos;
        $this->target = $target;
        $this->exceptionList = $exceptionList;
    }
    
    /**
     * 
     * @return string
     */
    public function getPos()
    {
        return $this->pos;
    }
    
    /**
     * 
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }
    
    /**
     * @return string[]
     */
    public function getExceptionList()
    {
        return $this->exceptionList;
    }
    
}
