<?php
namespace TextAnalysis\Queries;

use TextAnalysis\Indexes\InvertedIndex;

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
        return [$this->getQuery()[0] => $invertedIndex->getDocumentIdsByTerm($this->getQuery()[0])];
    }

}

