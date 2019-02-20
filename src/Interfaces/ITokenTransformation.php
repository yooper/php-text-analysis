<?php

namespace TextAnalysis\Interfaces;

/**
 *
 * @author yooper (yooper)
 */
interface ITokenTransformation
{
    /**
     * The transform function should return one of the following:
     * 1) the original word passed in
     * 2) a modified version of the word
     * 3) null value
     * @param string $word
     * @return string|null
     */
    public function transform($word);
}

