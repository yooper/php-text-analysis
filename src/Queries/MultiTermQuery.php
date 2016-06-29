<?php
namespace TextAnalysis\Queries;

use TextAnalysis\Tokenizers\WhitespaceTokenizer;
use TextAnalysis\Indexes\InvertedIndex;
use TextAnalysis\Utilities\Text;


/**
 * Handles search queries that have more than one query
 * @author yooper
 */
class MultiTermQuery extends QueryAbstractFactory
{
    /**
     * Returns an array of strings
     * @return array
     */
    public function getQuery()
    {
        return (new WhitespaceTokenizer())->tokenize($this->getQueryString());
    }

    /**
     * 
     * @param InvertedIndex $invertedIndex
     * @return array
     */
    public function queryIndex(InvertedIndex $invertedIndex) 
    {
        $terms = array_keys($invertedIndex->getIndex());        
        $found = [];
        
        foreach($terms as $term) 
        {
            foreach($this->getQuery() as $queryTerm)
            {
                if(Text::contains($term, $queryTerm)) {
                    $found[$term] = $invertedIndex->getDocumentIdsByTerm($term);
                }
            }
        }
        return $found;       
    }

}