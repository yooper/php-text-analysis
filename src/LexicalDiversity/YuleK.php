<?php
declare(strict_types = 1);

namespace TextAnalysis\LexicalDiversity;

/**
 * Description of YuleK
 *
 * @author yooper
 */
class YuleK extends YuleI implements \TextAnalysis\Interfaces\ILexicalDiversity
{        
    public function getDiversity(array $tokens): float 
    {
        return 1 / parent::getDiversity($tokens) * 10000;
    }
}
