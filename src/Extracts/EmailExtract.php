<?php

namespace TextAnalysis\Extracts;

use TextAnalysis\Interfaces\IExtractStrategy;

/**
 * Check the token if it is a email address,
 * @author yooper
 */
class EmailExtract implements IExtractStrategy
{
    
    /**
     * 
     * @param string $token
     * @todo  make it work on non-latin email address
     * @return mixed Returns false or an email address
     */
    public function filter($token)
    {
        return filter_var($token, FILTER_VALIDATE_EMAIL);
    }
}
