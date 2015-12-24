<?php
namespace TextAnalysis\Interfaces;
/**
 *
 * @author yooper (yooper)
 */
interface ITokenTransformation 
{
    /**
     * The transform function should return:
     * 1) the original word passed in
     * 2) a modified version of the word
     * 3) null value 
     */
    public function transform($word);
}

