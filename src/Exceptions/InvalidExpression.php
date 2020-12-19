<?php
declare(strict_types = 1);
namespace TextAnalysis\Exceptions;

/**
 * Used by the tokenization, primarily
 * @author yooper
 */
class InvalidExpression extends \Exception
{
    static public function invalidRegex($pattern, $replacement)
    {
        throw new InvalidExpression("The pattern '{$pattern}', and the replacement '{$replacement}' caused an error.");
    }
}

