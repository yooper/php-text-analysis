<?php

namespace TextAnalysis\Comparisons;

use TextAnalysis\Interfaces\IDistance;

/**
 * Wrapper for native php's levenshtein 
 *
 * @author Dan Cardin (yooper)
 */
class LevenshteinComparison implements IDistance
{
    protected $insertCost;
    protected $replaceCost;
    protected $deleteCost;
    
    /**
     * Customize the insert, replace, and delete cost. 
     * @param int $insertCost
     * @param int $replaceCost
     * @param int $deleteCost
     */
    public function __construct($insertCost = 1, $replaceCost = 1, $deleteCost = 1) 
    {
        $this->insertCost = $insertCost;
        $this->replaceCost = $replaceCost;
        $this->deleteCost = $deleteCost;
        
    }
    
    /**
     * Return the levenshtein distance, default costs of 1 applied
     * @param string $obj1
     * @param string $obj2
     * @return int
     */
    public function distance($obj1, $obj2)
    {
        return levenshtein($obj1, $obj2, $this->insertCost, $this->replaceCost, $this->deleteCost);
    }
}
