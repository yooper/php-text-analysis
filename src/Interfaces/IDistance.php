<?php
declare(strict_types = 1);

namespace TextAnalysis\Interfaces;

/**
 *
 * @author yooper (yooper)
 */
interface IDistance 
{
    public function distance($text1, $text2);
}
