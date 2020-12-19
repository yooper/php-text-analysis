<?php
declare(strict_types = 1);

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
