<?php
namespace TextAnalysis\Filters;

use TextAnalysis\Interfaces\ITokenTransformation;

/**
 * LambdaFilter lets the developer pass in a custom function to do the transformation
 * @author yooper
 */
class LambdaFilter implements ITokenTransformation
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
    
    /**
     * Run the lambda function on the word token
     * @param string $word
     * @return string|null
     */
    public function transform($word)
    {
        return call_user_func($this->lambdaFunc, $word);
    }
}
