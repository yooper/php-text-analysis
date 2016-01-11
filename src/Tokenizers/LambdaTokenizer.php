<?php


namespace TextAnalysis\Tokenizers;

/**
 * Roll your own tokenizer with this lamda function
 *
 * @author yooper
 */
class LambdaTokenizer extends TokenizerAbstract
{
    
    /**
     *
     * @var function
     */
    protected $lambdaFunc = null;

    /**
     * 
     * @param function $lambdaFunc
     */
    public function __construct($lambdaFunc) 
    {
        $this->lambdaFunc = $lambdaFunc;
    }
    
    
    public function tokenize($string) 
    {
        return call_user_func($this->lambdaFunc, $string);
    }

}
