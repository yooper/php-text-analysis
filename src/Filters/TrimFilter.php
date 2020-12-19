<?php
declare(strict_types = 1);

namespace TextAnalysis\Filters;

use TextAnalysis\Interfaces\ITokenTransformation;


/**
 *
 * @author yooper
 */
class TrimFilter implements ITokenTransformation
{
    //put your code here
    public function transform($word) 
    {
        return trim($word);
    }

}
