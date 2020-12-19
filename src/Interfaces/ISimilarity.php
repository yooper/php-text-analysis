<?php
declare(strict_types = 1);


namespace TextAnalysis\Interfaces;

/**
 *
 * @author yooper (yooper)
 */
interface ISimilarity 
{
    public function similarity($text1, $text2);
}
