<?php
namespace TextAnalysis\Documents;
use TextAnalysis\Interfaces\ITokenTransformation;
/**
 * Abstract parent document. 
 * @author Dan Cardin (yooper)
 */
abstract class DocumentAbstract
{
    /**
     * An array of tokens that all Documents have
     * @var type 
     */
    protected $tokens = array();
    
    
    /**
     * A name,class,type, or a metadata field, etc..
     * @var string 
     */
    protected $name;
    
    /**
     * 
     * @param array|null $tokens 
     */
    public function __construct(array $tokens = array(), $name = null)
    {
        $this->tokens = $tokens;
        $this->name = $name;
    }
    
    /**
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
    * Returns the set of tokens in this document, most of the time
    *  @return mixed
    */
    public abstract function getDocumentData();
    
    public abstract function applyTransformation(ITokenTransformation $transformer);
  
}
