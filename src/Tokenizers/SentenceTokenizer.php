<?php

namespace TextAnalysis\Tokenizers;

/**
 * Tokenize on periods, question marks and exclamations
 * @package Tokenizers\SentenceTokenizer
 * @source http://stackoverflow.com/questions/34881790/split-string-into-sentences-using-regex
 */
class SentenceTokenizer extends TokenizerAbstract
{
    /**
     *
     * @var string
     */
    protected $separator = null;
    
    public function __construct($separator = "\n\n") 
    {
        $this->separator = $separator;
    }

    /**
     * 
     * @return string
     */
    public function getSeparator()
    {
        return $this->separator;
    }

    
    /**
     * 
     * @param string $string
     * @return array
     */
    public function tokenize($string) 
    {
        $strings = explode($this->getSeparator(), $string);
        $sentenceTokens = [];
        foreach($strings as $str)
        {
            if(empty(trim($str))) {
                continue;
            }
            $sentences = $this->tokenizeSentence($str);
            foreach($sentences as $sentence)
            {
                $sentenceTokens[] = $sentence;
            }
        }
        return $sentenceTokens;
    }
    
    
    protected function tokenizeSentence($string) 
    {
        
        
        $before_regexes = array('/(?:(?:[\'\"„][\.!?…][\'\"”]\s)|(?:[^\.]\s[A-Z]\.\s)|(?:\b(?:St|Gen|Hon|Prof|Dr|Mr|Ms|Mrs|[JS]r|Col|Maj|Brig|Sgt|Capt|Cmnd|Sen|Rev|Rep|Revd)\.\s)|(?:\b(?:St|Gen|Hon|Prof|Dr|Mr|Ms|Mrs|[JS]r|Col|Maj|Brig|Sgt|Capt|Cmnd|Sen|Rev|Rep|Revd)\.\s[A-Z]\.\s)|(?:\bApr\.\s)|(?:\bAug\.\s)|(?:\bBros\.\s)|(?:\bCo\.\s)|(?:\bCorp\.\s)|(?:\bDec\.\s)|(?:\bDist\.\s)|(?:\bFeb\.\s)|(?:\bInc\.\s)|(?:\bJan\.\s)|(?:\bJul\.\s)|(?:\bJun\.\s)|(?:\bMar\.\s)|(?:\bNov\.\s)|(?:\bOct\.\s)|(?:\bPh\.?D\.\s)|(?:\bSept?\.\s)|(?:\b\p{Lu}\.\p{Lu}\.\s)|(?:\b\p{Lu}\.\s\p{Lu}\.\s)|(?:\bcf\.\s)|(?:\be\.g\.\s)|(?:\besp\.\s)|(?:\bet\b\s\bal\.\s)|(?:\bvs\.\s)|(?:\p{Ps}[!?]+\p{Pe} ))\Z/su',
            '/(?:(?:[\.\s]\p{L}{1,2}\.\s))\Z/su',
            '/(?:(?:[\[\(]*\.\.\.[\]\)]* ))\Z/su',
            '/(?:(?:\b(?:pp|[Vv]iz|i\.?\s*e|[Vvol]|[Rr]col|maj|Lt|[Ff]ig|[Ff]igs|[Vv]iz|[Vv]ols|[Aa]pprox|[Ii]ncl|Pres|[Dd]ept|min|max|[Gg]ovt|lb|ft|c\.?\s*f|vs)\.\s))\Z/su',
            '/(?:(?:\b[Ee]tc\.\s))\Z/su',
            '/(?:(?:[\.!?…]+\p{Pe} )|(?:[\[\(]*…[\]\)]* ))\Z/su',
            '/(?:(?:\b\p{L}\.))\Z/su',
            '/(?:(?:\b\p{L}\.\s))\Z/su',
            '/(?:(?:\b[Ff]igs?\.\s)|(?:\b[nN]o\.\s))\Z/su',
            '/(?:(?:[\"”\']\s*))\Z/su',
            '/(?:(?:[\.!?…][\x{00BB}\x{2019}\x{201D}\x{203A}\"\'\p{Pe}\x{0002}]*\s)|(?:\r?\n))\Z/su',
            '/(?:(?:[\.!?…][\'\"\x{00BB}\x{2019}\x{201D}\x{203A}\p{Pe}\x{0002}]*))\Z/su',
            '/(?:(?:\s\p{L}[\.!?…]\s))\Z/su');
        $after_regexes = array('/\A(?:)/su',
            '/\A(?:[\p{N}\p{Ll}])/su',
            '/\A(?:[^\p{Lu}])/su',
            '/\A(?:[^\p{Lu}]|I)/su',
            '/\A(?:[^p{Lu}])/su',
            '/\A(?:\p{Ll})/su',
            '/\A(?:\p{L}\.)/su',
            '/\A(?:\p{L}\.\s)/su',
            '/\A(?:\p{N})/su',
            '/\A(?:\s*\p{Ll})/su',
            '/\A(?:)/su',
            '/\A(?:\p{Lu}[^\p{Lu}])/su',
            '/\A(?:\p{Lu}\p{Ll})/su');
        $is_sentence_boundary = array(false, false, false, false, false, false, false, false, false, false, true, true, true);
        $count = count($is_sentence_boundary);

        $sentences = [];
        $sentence = '';
        $before = '';
        $after = substr($string, 0, 10);
        $string = substr($string, 10);

        while($string != '') 
        {
            for($i = 0; $i < $count; $i++) 
            {
                if(preg_match($before_regexes[$i], $before) && preg_match($after_regexes[$i], $after)) {
                    if($is_sentence_boundary[$i]) {
                        $sentences[] = $sentence;
                        $sentence = '';
                    }
                    break;
                }
            }

            $first_from_text = $string[0];
            $string = substr($string, 1);
            $first_from_after = $after[0];
            $after = substr($after, 1);
            $before .= $first_from_after;
            $sentence .= $first_from_after;
            $after .= $first_from_text;
        }

        if(!empty($sentence) && !empty($after)) {
            $sentences[] = $sentence.$after;
        }

        // perform some cleanup, and re-index the array
        return array_values(array_filter(array_map('trim',$sentences)));
    }
     
}

