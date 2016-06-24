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
     * Returns an array of strings
     * @return array
     */
    public function getQuery()
    {
        return (new WhitespaceTokenizer())->tokenize($this->getQueryString());
    }
}