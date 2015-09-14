<?php

namespace TextAnalysis\Interfaces;

/**
 *
 * @author yooper
 */
interface IExtractStrategy 
{
    /**
     * Returns false or the token
     * @param string $token
     */
    public function filter($token);
}
