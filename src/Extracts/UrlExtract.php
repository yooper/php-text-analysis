<?php

namespace TextAnalysis\Extracts;

use TextAnalysis\Interfaces\IExtractStrategy;

/**
 * Extract out a URL if one exists
 * @author yooper
 */
class UrlExtract implements IExtractStrategy
{
    /**
     * 
     * @param string $token
     * @return mixed
     */
    public function filter($token) 
    {
        return filter_var($token, FILTER_VALIDATE_URL);
    }
}
