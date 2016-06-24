<?php
namespace TextAnalysis\Queries;


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
}

