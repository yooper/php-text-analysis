<?php

namespace TextAnalysis\Corpus;

use TextAnalysis\Tokenizers\GeneralTokenizer;
use TextAnalysis\LexicalDiversity\Naive;
use TextAnalysis\Utilities\Text;

/**
 * Explore the text corpus
 * @author yooper
 */
class TextCorpus
{
    /**
     *
     * @var string
     */
    protected $text;

    /**
     *
     * @var array
     */
    protected $tokens = [];

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    /**
     * Returns the original text
     * @return string
     */
    public function getText() : string
    {
        return $this->text;
    }

    public function getTokens(string $tokenizerClassName = GeneralTokenizer::class) : array
    {
        if(empty($this->tokens)) {
            $this->tokens = tokenize($this->getText(), $tokenizerClassName);
        }
        return $this->tokens;
    }

    /**
     * Return a list of positions that the needs were found in the text
     * @param array $needles
     * @return int[]
     */
    public function getDispersion(array $needles) : array
    {
        $found = array_fill_keys($needles, []);
        foreach(array_keys($needles) as $needle)
        {
            $found[$needle] = $this->findAll($needle);
        }
        return $found;
    }

    /**
     * Compute the lexical diversity, the default uses a naive algorithm
     * @param string $lexicalDiversityClassName
     * @return float
     */
    public function getLexicalDiversity(string $lexicalDiversityClassName = Naive::class) : float
    {
        return lexical_diversity($this->getTokens(), $lexicalDiversityClassName);
    }

    /**
     * See https://stackoverflow.com/questions/15737408/php-find-all-occurrences-of-a-substring-in-a-string
     * @param string $needle
     * @param int $contextLength The amount of space left and right of the found needle
     * @param bool $ignorecase
     * @param int $position. Available options: contain, begin, end, equal.
     * @return array
     */
    public function concordance(string $needle, int $contextLength = 20, bool $ignorecase = true, string $position = 'contain') : array
    {
        // temporary solution to handle unicode chars
        $this->text = utf8_decode($this->text);
        $needle = utf8_decode($needle);

        $found = [];
        $text = ' ' . trim(preg_replace('/[\s\t\n\r\s]+/', ' ', $this->text)) . ' ';
        $needleLength = strlen($needle);
        $textLength = strlen($text);
        $bufferLength = $needleLength + 2 * $contextLength;

        // \p{L} or \p{Letter}: any kind of letter from any language.

        $special_chars = "\/\-_\'";
        $word_part = '\p{L}'.$special_chars;

        switch ($position) {
            case 'equal':
                $pattern = "/[^$word_part]($needle)[^$word_part]/";
                break;
            case 'begin':
                $pattern = "/[^$word_part]($needle)[$special_chars]?[\p{L}]*|^($needle)/";
                break;
            case 'end':
                $pattern = "/[\p{L}]*[$special_chars]?[\p{L}]*($needle)[^$word_part]/";
                break;
            case 'contain':
                $pattern = "/($needle)/";
                break;
            default:
                $pattern = "/($needle)/";
                break;
        }

        $case = $ignorecase ? 'i' : '';
        preg_match_all($pattern.$case, $text, $matches, PREG_OFFSET_CAPTURE);

        // Getting excerpts
        foreach($matches[1] as $match) {

            $needlePosition = $match[1];
            $left = max($needlePosition - $contextLength, 0);

            if($needleLength + $contextLength + $needlePosition > $textLength) {
                $tmp = substr($text, $left);
            } else {
                $tmp = substr($text, $left, $bufferLength);
            }
            $found[] = utf8_encode($tmp);
        }

        return $found;
    }

    public function occurrences(string $needle, int $contextLength = 20, bool $ignorecase = true, string $position = 'contain', bool $mark = false) : array
    {
        // temporary solution to handle unicode chars
        $this->text = utf8_decode($this->text);
        $needle = utf8_decode($needle);

        $found = [];
        $text = ' ' . trim(preg_replace('/[\s\t\n\r\s]+/', ' ', $this->text)) . ' ';
        $needleLength = strlen($needle);
        $textLength = strlen($text);
        $bufferLength = $needleLength + 2 * $contextLength;

        // \p{L} or \p{Letter}: any kind of letter from any language.

        $special_chars = "\/\-_\'";
        $word_part = '\p{L}'.$special_chars;

        switch ($position) {
            case 'equal':
                $pattern = "/[^$word_part]($needle)[^$word_part]/";
                break;
            case 'begin':
                $pattern = "/[^$word_part]($needle)[$special_chars]?[\p{L}]*|^($needle)/";
                break;
            case 'end':
                $pattern = "/[\p{L}]*[$special_chars]?[\p{L}]*($needle)[^$word_part]/";
                break;
            case 'contain':
                $pattern = "/($needle)/";
                break;
            default:
                $pattern = "/($needle)/";
                break;
        }

        $case = $ignorecase ? 'i' : '';
        preg_match_all($pattern.$case, $text, $matches, PREG_OFFSET_CAPTURE);

        $positions = array_column($matches[1], 1);

        $excerpts = array_map(function($needlePos) use ($needleLength, $text, $contextLength, $mark) {
            return $this->extractExcerptTerm($needlePos, $needleLength, $text, $contextLength, $mark);
        }, $positions);

        return $excerpts;
    }

    /**
    * Mark the neddle and get its context
    *
    * @return String
    */
    private function extractExcerptTerm(int $needlePos, int $needleLength, String $text, int $contextLength, bool $mark = false)
    {
        //marking the term
        $text = ($mark) ? Text::markString($text, $needlePos, $needleLength, ['{{','}}']) : $text;
        $needleLength = ($mark) ? $needleLength+4 : $needleLength;

        //extracts the excerpt
        $text = Text::getExcerpt($text, $needlePos, $needleLength, $contextLength);

        return utf8_encode($text);
    }

    /**
     * Get percentage of times the needle shows up in the text
     * @param string $needle
     * @return float
     */
    public function percentage(string $needle) : float
    {
        $freqDist = freq_dist($this->getTokens());
        return $freqDist->getKeyValuesByFrequency()[$needle] / $freqDist->getTotalTokens();
    }

    /**
     * Performs a case insensitive search for the needle
     * @param string $needle
     * @return int
     */
    public function count(string $needle) : int
    {
        return substr_count(strtolower($this->getText()), strtolower($needle));
    }

    /**
     * Return all the position of the needle found in the text
     * @param string $needle
     * @return array
     */
    public function findAll(string $needle) : array
    {
        $lastPos = 0;
        $positions = [];
        $needle = strtolower($needle);
        $text = strtolower($this->getText());
        $needleLength = strlen($needle);
        while (($lastPos = stripos($text, $needle, $lastPos))!== false)
        {
            $positions[] = $lastPos;
            $lastPos += $needleLength;
        }
        return $positions;
    }
    public function toString()
    {
        return $this->text;
    }

    public function __destruct()
    {
        unset($this->text);
        unset($this->tokens);
    }
}
