<?php
declare(strict_types = 1);
namespace TextAnalysis\Interfaces;

/**
 * @author yooper
 */
interface IStemmer
{
    /**
     * Returns a token that may or may not have been modified 
     */
    public function stem($token);
}

