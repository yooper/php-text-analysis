<?php


namespace TextAnalysis\Interfaces;

/**
 *
 * @author Dan Cardin (yooper)
 */
interface ISimilarity 
{
    public function similarity($text1, $text2);
}
