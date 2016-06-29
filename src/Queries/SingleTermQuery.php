<?php
namespace TextAnalysis\Queries;

use TextAnalysis\Indexes\InvertedIndex;
use TextAnalysis\Utilities\Text;

/**
 * Handles search queries that only have a single term
 * @author yooper
 */
class SingleTermQuery extends QueryAbstractFactory
{
    /**
     * Returns a single string no spaces inside an array
     * @return array
     */
    public function getQuery()
    {
        return [$this->getQueryString()];
    }

    /**
     * @param InvertedIndex $invertedIndex
     * @return arrray
     */
    public function queryIndex(InvertedIndex $invertedIndex) 
    {        
        $r = $invertedIndex->getDocumentIdsByTerm($this->getQuery()[0]);
        if(!empty($r)) {
            return [$this->getQuery()[0] => $r];
        }
        
        // do partial matches
        $terms = array_keys($invertedIndex->getIndex());        
        $found = [];
        
        foreach($terms as $term) 
        {
            if(Text::contains($term, $this->getQueryString())) {
                $found[$term] = $invertedIndex->getDocumentIdsByTerm($term);
            }
        }
        return $found;                 
    }

}

