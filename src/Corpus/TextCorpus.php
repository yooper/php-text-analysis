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
     * @param bool $mark Option to mark the needle
     * @return array
     */
    public function concordance(string $needle, int $contextLength = 20, bool $ignorecase = true, string $position = 'contain', bool $mark = false) : array
    {
        // temporary solution to handle unicode chars
        $text = utf8_decode($this->text);
        $text = trim(preg_replace('/[\s\t\n\r\s]+/', ' ', $text));
        $needle = utf8_decode($needle);
        $needleLength = strlen($needle);
        $found = [];

        $positions = $this->concordancePositions($text, $needle, $contextLength, $ignorecase, $position);

        // Getting excerpts
        foreach($positions as $needlePosition) {
            //marking the term
            $text_marked = ($mark) ? Text::markString($text, $needlePosition, $needleLength, ['{{','}}']) : $text;
            $needleLength_marked = ($mark) ? $needleLength+4 : $needleLength;

            $found[] = utf8_encode(Text::getExcerpt($text_marked, $needlePosition, $needleLength_marked, $contextLength));
        }

        return $found;
    }

    /**
     * Return all positions of the needle in the text according to the position of the needle in a word.
     * @param string $text
     * @param int $needle
     * @param int $contextLength The amount of space left and right of the found needle
     * @param bool $ignorecase
     * @param int $position. Available options: contain, begin, end, equal.
     * @return array
     */
    public function concordancePositions(string $text, string $needle, int $contextLength = 20, bool $ignorecase = true, string $position = 'contain') : array
    {
        $found = [];
        $needleLength = strlen($needle);
        $textLength = strlen($text);
        $bufferLength = $needleLength + 2 * $contextLength;

        // \p{L} or \p{Letter}: any kind of letter from any language.

        $special_chars = "\/\-_\'";
        $word_part = '\p{L}'.$special_chars;

        switch ($position) {
            case 'equal':
                $pattern = "/(?<![$word_part])($needle)(?![$word_part])/";
                break;
            case 'begin':
                $pattern = "/(?<![$word_part])($needle)[$special_chars]?[\p{L}]*|^($needle)/";
                break;
            case 'end':
                $pattern = "/[\p{L}]*[$special_chars]?[\p{L}]*($needle)(?![$word_part])/";
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

        return $positions;
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
