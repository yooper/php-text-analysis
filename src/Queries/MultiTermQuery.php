<?php
namespace TextAnalysis\Queries;

use TextAnalysis\Tokenizers\WhitespaceTokenizer;


/**
 * Handles search queries that have more than one query
 * @author yooper
 */
class MultiTermQuery extends QueryAbstractFactory
{
    /**
     * Returns a single string no spaces
     * @return string
     */
    public function getQuery()
    {
        $ws = new WhitespaceTokenizer();
        return $ws->tokenize($this->queryString);
    }
}