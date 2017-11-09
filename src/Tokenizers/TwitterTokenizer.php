<?php


namespace TextAnalysis\Tokenizers;

/**
 * Tokenize tweets, based on http://www.nltk.org/_modules/nltk/tokenize/casual.html
 * @author yooper
 */
class TwitterTokenizer extends TokenizerAbstract
{       
    public function tokenize($string): array 
    {
        $matches = [];
        $found = preg_match_all("~{$this->getJoinedRegexes()}~uim", $string, $matches);
        if($found === 0) {
            return [];
        }
        return $matches[0];
    }
    
    /**
     * Return an array of regexes, ordered by priority
     */
    public function getRegexes() : array
    {
        return [
            $this->getPhoneNumbers(),                        
            $this->getUrls(),            
            $this->getEmoticons(),            
            $this->getHashTags(),           
            $this->getAsciiArrows(),            
            $this->getUsernames(),
            $this->getHashTags(),            
            $this->getWordsWith(),
            $this->getNumbers(),            
            $this->getWordsWithout(),            
            $this->getEllipsisDots(),            
            $this->getEverythingElse()
        ];
    }
    
    /**
     * 
     * @return string
     */
    public function getJoinedRegexes() : string
    {
        return implode("|", $this->getRegexes());
    }
            
    public function getHtmlTags() : string
    {
        return '<[^>\s]+>';
    }
    
    public function getAsciiArrows() : string
    {
        return '[\-]+>|<[\-]+';
    }
    
    public function getUsernames() : string
    {
        return '(?:@[\w_]+)';
    }
    
    public function getHashTags() : string
    {
        return '(?:\#+[\w_]+[\w\'_\-]*[\w_]+)';
    }
    
    /**
     * # Words with apostrophes or dashes.
     * @return string
     */
    public function getWordsWith() : string
    {
        return "(?:[^\W\d_](?:[^\W\d_]|['\-_])+[^\W\d_])";
    }
    
    public function getEmailAddress() : string
    {
        return '[\w.+-]+@[\w-]+\.(?:[\w-]\.?)+[\w-]';
    }
    
    public function getNumbers() : string
    {
        return '(?:[+\-]?\d+[,/.:-]\d+[+\-]?)';
    }
    
    /**
     * Words without apostrophes or dashes.
     * @return string
     */
    public function getWordsWithout() : string
    {
        return '(?:[\w_]+)'; 
    }
    
    public function getEllipsisDots() : string
    {
        return '(?:\.(?:\s*\.){1,})';
    }
    
    /**
     * Everything else that isn't whitespace.
     * @return string
     */
    public function getEverythingElse() : string
    {
        return '(?:\S)';
    }
            
    /**
     * Taken from https://stackoverflow.com/questions/2113908/what-regular-expression-will-match-valid-international-phone-numbers
     * @return string
     */
    public function getPhoneNumbers() : string
    {
        return '(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?';
    }
    
    /**
     * Taken from https://stackoverflow.com/questions/6427530/regular-expression-pattern-to-match-url-with-or-without-http-www
     * @return string
     */
    public function getUrls() : string
    {
        $regex = '((https?|ftp)://)?'; // SCHEME
        $regex .= '([a-z0-9+!*(),;?&=$_.-]+(:[a-z0-9+!*(),;?&=$_.-]+)?@)?'; // User and Pass
        $regex .= '([a-z0-9\-\.]*)\.(([a-z]{2,4})|([0-9]{1,3}\.([0-9]{1,3})\.([0-9]{1,3})))'; // Host or IP
        $regex .= "(:[0-9]{2,5})?"; // Port
        $regex .= '(/([a-z0-9+$_%-]\.?)+)*/?'; // Path
        $regex .= '(\?[a-z+&\$_.-][a-z0-9;:@&%=+/$_.-]*)?'; // GET Query
        $regex .= '(#[a-z_.-][a-z0-9+$%_.-]*)?'; // Anchor
        return $regex;
    }
    
    public function getEmoticons() : string
    {           
        return '(\:\w+\:|\<[\/\\]?3|[\(\)\\\D|\*\$][\-\^]?[\:\;\=]|[\:\;\=B8][\-\^]?[3DOPp\@\$\*\\\)\(\/\|])(?=\s|[\!\.\?]|$)';
    }

}
