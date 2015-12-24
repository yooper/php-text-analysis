<?php
namespace TextAnalysis\Documents;
use TextAnalysis\Interfaces\ITokenTransformation;
/**
 * Abstract parent document. 
 * @author yooper (yooper)
 */
abstract class DocumentAbstract
{
    /**
     * An array of tokens that all Documents have
     * @var type 
     */
    protected $tokens = array();
    
    /**
     * Zones provides a stdClass for adding in the desired metadata/zones in this document
     * leave as public to simplify access
     * @var \stdClass
     */
    public $zones;
    
    /**
     * 
     * @param array|null $tokens 
     * @param \stdClass|null $zones
     */
    public function __construct(array $tokens = array(), $zones = null)
    {
        $this->tokens = $tokens;
        if(!$zones) { 
            $this->zones = new \stdClass();
        }
    }
    
    /**
    * Returns the set of tokens in this document, most of the time
    *  @return mixed
    */
    public abstract function getDocumentData();
    
    public abstract function applyTransformation(ITokenTransformation $transformer);
  
}
