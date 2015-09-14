<?php

namespace TextAnalysis\Interfaces;

/**
 * Create a generic interface for dictionary adapter implementations to use
 * @author yooper
 */
interface ISpelling 
{
    /**
     * Return an array of suggested words
     * @param string $word
     * @return array
     */
    public function suggest($word);
}
