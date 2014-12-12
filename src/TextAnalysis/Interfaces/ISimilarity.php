<?php


namespace TextAnalysis\Interfaces;

/**
 *
 * @author Dan Cardin (yooper)
 */
interface ISimilarity 
{
    public function similarity($obj1, $obj2);
}
