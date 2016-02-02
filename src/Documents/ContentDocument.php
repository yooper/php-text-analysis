<?php

namespace TextAnalysis\Documents;

/**
 * ContentDocument is a generic document class that takes a string for its content
 * parsing/tokenizing the string is performed afterwards
 * @author yooper
 */
class ContentDocument 
{
    static protected $counter = 0;
    
    /**
     *
     * @var mixed
     */
    protected $id = null; 
    
    /**
     *
     * @var string
     */
    protected $content = null;
    
    /**
     * 
     * @param string $content
     * @param mixed $id uses a counter if empty
     */
    public function __construct($content, $id = null) 
    {
        if(!$id) { 
            $this->id = ++self::$counter;
        }
        $this->content = $content;
    }
    
    /**
     * 
     * @return int|string
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * 
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}
