<?php

namespace TextAnalysis\Queries;

use TextAnalysis\Tokenizers\WhitespaceTokenizer;
use TextAnalysis\Indexes\InvertedIndex;

/**
 * When strings are quoted in the start and end, it must be a quoted string
 * which is going to use boolean ANDs in the search
 * @author yooper
 */
class QuotedQuery extends MultiTermQuery
{
    /**
     * @return array
     */
    public function getQuery()
    {
        return (new WhitespaceTokenizer())->tokenize( substr( $this->getQueryString(), 1, -1));
    }

    /**
     * Return an array of terms and doc ids where they are located
     * @param InvertedIndex $invertedIndex
     * @return array
     */
    public function queryIndex(InvertedIndex $invertedIndex) 
    {
        $found = parent::queryIndex($invertedIndex);
        
        $foundCount = count($found);
        $docIds = [];
        $andResults = [];
                
        foreach($found as $posts)
        {
            $docIds = array_merge($docIds, array_values($posts));
        }
        
        $freqTable = array_count_values($docIds);                
        foreach($freqTable as $docId => $tally)
        {
            if($foundCount === $tally) {
                $andResults[] = $docId;
            }
        }
        
        // no results
        if(empty($andResults)) {
            return [];
        }        
        return array_fill_keys(array_keys($found), $andResults);                  
    }

}
