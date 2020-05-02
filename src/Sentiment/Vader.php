<?php

namespace TextAnalysis\Sentiment;

/**
 * Implementation of the Vader Sentiment algorithm
 * 
 * Hutto, C.J. & Gilbert, E.E. (2014). VADER: A Parsimonious Rule-based Model for
 * Sentiment Analysis of Social Media Text. Eighth International Conference on
 * Weblogs and Social Media (ICWSM-14). Ann Arbor, MI, June 2014.
 * 
 * Implementation mostly taken from nltk
 * @author yooper
 */
class Vader 
{
    // (empirically derived mean sentiment intensity rating increase for booster words)
    CONST B_INCR = 0.293;
    CONST B_DECR = -0.293;

    // (empirically derived mean sentiment intensity rating increase for using
    // ALLCAPs to emphasize a word)
    CONST C_INCR = 0.733;
    CONST N_SCALAR = -0.74;

    // for removing punctuation    
    public $puncList = [".", "!", "?", ",", ";", ":", "-", "'", "\"", "!!", "!!!", "??", "???", "?!?", "!?!", "?!?!", "!?!?"];
    
    public $negate = ["aint", "arent", "cannot", "cant", "couldnt", "darent", "didnt", "doesnt",
        "ain't", "aren't", "can't", "couldn't", "daren't", "didn't", "doesn't",
        "dont", "hadnt", "hasnt", "havent", "isnt", "mightnt", "mustnt", "neither",
        "don't", "hadn't", "hasn't", "haven't", "isn't", "mightn't", "mustn't",
        "neednt", "needn't", "never", "none", "nope", "nor", "not", "nothing", "nowhere",
        "oughtnt", "shant", "shouldnt", "uhuh", "wasnt", "werent",
        "oughtn't", "shan't", "shouldn't", "uh-uh", "wasn't", "weren't",
        "without", "wont", "wouldnt", "won't", "wouldn't", "rarely", "seldom", "despite"];
    
    // booster/dampener 'intensifiers' or 'degree adverbs'
    // http://en.wiktionary.org/wiki/Category:English_degree_adverbs

    protected $boosterDict = [
         "absolutely"=> Vader::B_INCR, "amazingly"=> Vader::B_INCR, "awfully"=> Vader::B_INCR, "completely"=> Vader::B_INCR, "considerably"=> Vader::B_INCR,
         "decidedly"=> Vader::B_INCR, "deeply"=> Vader::B_INCR, "effing"=> Vader::B_INCR, "enormously"=> Vader::B_INCR,
         "entirely"=> Vader::B_INCR, "especially"=> Vader::B_INCR, "exceptionally"=> Vader::B_INCR, "extremely"=> Vader::B_INCR,
         "fabulously"=> Vader::B_INCR, "flipping"=> Vader::B_INCR, "flippin"=> Vader::B_INCR,
         "fricking"=> Vader::B_INCR, "frickin"=> Vader::B_INCR, "frigging"=> Vader::B_INCR, "friggin"=> Vader::B_INCR, "fully"=> Vader::B_INCR, "fucking"=> Vader::B_INCR,
         "greatly"=> Vader::B_INCR, "hella"=> Vader::B_INCR, "highly"=> Vader::B_INCR, "hugely"=> Vader::B_INCR, "incredibly"=> Vader::B_INCR,
         "intensely"=> Vader::B_INCR, "majorly"=> Vader::B_INCR, "more"=> Vader::B_INCR, "most"=> Vader::B_INCR, "particularly"=> Vader::B_INCR,
         "purely"=> Vader::B_INCR, "quite"=> Vader::B_INCR, "really"=> Vader::B_INCR, "remarkably"=> Vader::B_INCR,
         "so"=> Vader::B_INCR, "substantially"=> Vader::B_INCR,
         "thoroughly"=> Vader::B_INCR, "totally"=> Vader::B_INCR, "tremendously"=> Vader::B_INCR,
         "uber"=> Vader::B_INCR, "unbelievably"=> Vader::B_INCR, "unusually"=> Vader::B_INCR, "utterly"=> Vader::B_INCR,
         "very"=> Vader::B_INCR,
         "almost"=> Vader::B_DECR, "barely"=> Vader::B_DECR, "hardly"=> Vader::B_DECR, "just enough"=> Vader::B_DECR,
         "kind of"=> Vader::B_DECR, "kinda"=> Vader::B_DECR, "kindof"=> Vader::B_DECR, "kind-of"=> Vader::B_DECR,
         "less"=> Vader::B_DECR, "little"=> Vader::B_DECR, "marginally"=> Vader::B_DECR, "occasionally"=> Vader::B_DECR, "partly"=> Vader::B_DECR,
         "scarcely"=> Vader::B_DECR, "slightly"=> Vader::B_DECR, "somewhat"=> Vader::B_DECR,
         "sort of"=> Vader::B_DECR, "sorta"=> Vader::B_DECR, "sortof"=> Vader::B_DECR, "sort-of"=> Vader::B_DECR    
    ];
    
    // check for special case idioms using a sentiment-laden keyword known to VADER
    public $specialCaseIdioms = [
        "the shit"=> 3, "the bomb"=> 3, "bad ass"=> 1.5, "yeah right"=> -2,
        "cut the mustard"=> 2, "kiss of death"=> -1.5, "hand to mouth"=> -2
    ];
    
    /**
     * The lexicon dataset loaded from the flat file
     * @var array 
     */
    protected $lexicon = [];
    
    /**
     * Initializes and loads the lexicon
     */
    public function __construct() 
    {

    }

    /**
     * Add a new token and score to the lexicon
     * @param string $token
     * @param float $meanSentimentRating
     */
    public function addToLexicon(string $token, float $meanSentimentRating)
    {
        $this->lexicon[$token] = $meanSentimentRating;
    }
    
    /**
     * Remove a token from the lexicon
     * @param string $token
     */
    public function deleteFromLexicon(string $token)
    {
        unset($this->lexicon[$token]);
    }
    
    
    /**
     *      
     * Determine if input contains negation words
     * @param array $tokens
     * @param bool $includeNt
     * @return bool
     */    
    public function isNegated(array $tokens, bool $includeNt = true) : bool
    {
        foreach($tokens as $word)
        {
            if(in_array($word, $this->negate) || 
                    ($includeNt && strpos($word, "n't") !== false) ||
                    ( strpos($word, 'least') > 0 && strpos($word, 'at') !== 0 ) ) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Normalize the score to be between -1 and 1 using an alpha that
     * approximates the max expected value
     * @param float $score
     * @param int $alpha
     */
    public function normalize(float $score, int $alpha=15)
    {
        $normalizedScore = $score;

        if (sqrt(($score^2) + $alpha > 0)) {
            $normalizedScore = $score/sqrt(($score^2) + $alpha);
        }

        if ($normalizedScore < -1.0) {
            return -1.0;
        } elseif ($normalizedScore > 1.0) {
            return 1.0;
        }
        return $normalizedScore;
    }
    
    /**
     * Check if the preceding words increase, decrease, or negate/nullify the valence
     * @param string $word
     * @param float $valence
     * @param bool $isCapDiff
     * @return float
     */
    public function scalarIncDec(string $word, float $valence, bool $isCapDiff)
    {
        $scalar = 0.0;
        $wordLower = strtolower($word);
        if(isset($this->boosterDict[$wordLower]))
        {
            $scalar = $this->boosterDict[$wordLower];
            if($valence < 0) {
                $scalar *= -1;
            }
            //check if booster/dampener word is in ALLCAPS (while others aren't)
            if(strtoupper($word) && $isCapDiff) {
                if ($valence > 0) {
                    $scalar += Vader::C_INCR;
                } else { 
                    $scalar -= Vader::C_INCR;
                }
            }
        }
        return $scalar;
    }
    
    /**
     * Return a float for sentiment strength based on the input text.
     * Positive values are positive valence, negative value are negative valence.
     * @param array $tokens
     */
    public function getPolarityScores(array $tokens) : array
    {
        $sentiments = [];
        for($index = 0; $index < count($tokens); $index++)
        {
            $valence = 0.0;
            $lcToken = strtolower($tokens[$index]);
            if( $lcToken === "kind" 
            && (array_key_exists($index+1, $tokens) && strtolower($tokens[$index+1]) === 'of') ||
                isset(self::$this->boosterDict[$lcToken]) ) { 
                
                $sentiments[] = $valence;
            } else {
                $sentiments[] = $this->getSentimentValence($valence, $tokens, $index, $sentiments);
            }
        }

        $sentiments = $this->butCheck($tokens, $sentiments);        
        return $this->scoreValence($sentiments, $tokens);
    }
    
    public function scoreValence(array $sentiments, array $tokens)
    {
        if ( !empty($sentiments)) {
            $sentimentSum = array_sum($sentiments);
            // compute and add emphasis from punctuation in text
            $punctAmplifier = $this->boostExclamationPoints($tokens) + $this->boostQuestionMarks($tokens);
            if ($sentimentSum > 0) {
                $sentimentSum += $punctAmplifier;
            } elseif ($sentimentSum < 0) {
                $sentimentSum -= $punctAmplifier;
            }

            $compound = $this->normalize($sentimentSum);
            // discriminate between positive, negative and neutral sentiment scores
            list($posSum, $negSum, $neuCount) = $this->siftSentimentScores($sentiments);
            
            if ($posSum > abs($negSum)) {
                $posSum += $punctAmplifier;
            } elseif($posSum < abs($negSum)) {
                $negSum -= $punctAmplifier;
            }

            $total = $posSum + abs($negSum) + $neuCount;
            $pos = abs($posSum / $total);
            $neg = abs($negSum / $total);
            $neu = abs($neuCount / $total);

        } else {
            $compound = 0.0;
            $pos = 0.0;
            $neg = 0.0;
            $neu = 0.0;
        }

        return [
            "neg" => round($neg, 3),
            "neu" => round($neu, 3),
            "pos" => round($pos, 3),
            "compound" => round($compound, 4)
        ];
    }
    
    public function getSentimentValence(float $valence, array $tokens, int $index)
    {
        $isCapDiff = $this->allCapDifferential($tokens);
        $lcToken = strtolower($tokens[$index]);
        $ucToken = strtoupper($tokens[$index]);
        
        if(isset($this->getLexicon()[$lcToken]))
        {
            //get the sentiment valence
            $valence = $this->getLexicon()[$lcToken];
            //check if sentiment laden word is in ALL CAPS (while others aren't)
            if ($ucToken and $isCapDiff) { 
                if ($valence > 0) {
                    $valence += self::C_INCR;
                } else {
                    $valence -= self::C_INCR;
                }
            }

            for($startIndex = 0; $startIndex <= 3; $startIndex++)
            {
                if ($index > $startIndex && !isset($this->getLexicon()[ strtolower($tokens[$index-($startIndex+1)])]))
                {
                    // dampen the scalar modifier of preceding words and emoticons
                    // (excluding the ones that immediately preceed the item) based
                    // on their distance from the current item.
                    $s = $this->scalarIncDec($tokens[$index-($startIndex+1)], $valence, $isCapDiff);
                    if ($startIndex == 1 and $s != 0) {
                        $s *= 0.95;
                    } elseif ($startIndex == 2 and $s != 0 ) {
                        $s *= 0.9;
                    }
                    $valence += $s;
                    $valence = $this->neverCheck($valence, $tokens, $startIndex, $index);
                    if ($startIndex == 2) {
                        $valence = $this->idiomsCheck($valence, $tokens, $index);
                    }

                    // future work: consider other sentiment-laden idioms
                    // other_idioms =
                    // {"back handed": -2, "blow smoke": -2, "blowing smoke": -2,
                    // "upper hand": 1, "break a leg": 2,
                    //  "cooking with gas": 2, "in the black": 2, "in the red": -2,
                    //  "on the ball": 2,"under the weather": -2}
                }
                $valence = $this->leastCheck($valence, $tokens, $index);
            }

        }
        return $valence;
    }
    
    public function idiomsCheck(float $valence, array $tokens, int $index)
    {
        $bigrams = ngrams(array_slice($tokens, $index-2, 2), 2);
        $trigrams = ngrams(array_slice($tokens, $index-3, 3), 3);

        foreach( $bigrams + $trigrams as $ngram)
        {
            if(isset(self::$this->specialCaseIdioms[$ngram])) {
                $valence = self::$this->specialCaseIdioms[$ngram];
            }
            
            if(isset(self::$this->boosterDict[$ngram])) {
                $valence += self::$this->boosterDict[$ngram];
            }
        }

        return $valence;
    }
    
    
    /**
     * check for negation case using "least"
     * @param float $valence
     * @param array $tokens
     * @param int $index
     * @return float
     */
    public function leastCheck(float $valence, array $tokens, int $index) : float
    {
        if($index === 0) {
            return $valence;
        }
        
        $inLexicon = isset($this->getLexicon()[strtolower($tokens[$index-1])]);
        
        if($inLexicon) {
            return $valence;                       
        }
        
        $leastIndex = array_searchi('least', $tokens);
        $atIndex = array_searchi('at', $tokens);
        $veryIndex = array_searchi('very', $tokens);
        
        if($leastIndex == $index-1 && $atIndex != $index-2 && $veryIndex != $index-2) {
            $valence *= self::N_SCALAR;
        } elseif( $leastIndex == $index-1) {
            $valence *= self::N_SCALAR;
        }
        
        return $valence;  
    }
    
    /**
     * check for modification in sentiment due to contrastive conjunction 'but'
     * @param array $tokens
     * @param array $sentiments
     * @return array|real
     */
    public function butCheck(array $tokens, array $sentiments)
    {
        $index = array_searchi('but', $tokens);
        // no modification required
        if($index === false) {
            return $sentiments;
        }
        
        for($i = 0; $i < count($sentiments); $i++)
        {
            if( $index < $i) {
                $sentiments[$i] *= 0.5;
            } elseif($index > $i) {
                $sentiments[$i] *= 1.5;
            }
        }
        return $sentiments;
    }
    
    /**
     * 
     * @param array $sentimentScores
     * @return array
     */
    public function siftSentimentScores(array $sentimentScores) : array
    {
        // want separate positive versus negative sentiment scores
        $posSum = 0.0;
        $negSum = 0.0;
        $neuCount = 0;
        foreach($sentimentScores as $sentimentScore)
        {
            
            if($sentimentScore > 0) {
                $posSum += $sentimentScore + 1; // compensates for neutral words that are counted as 1
            } elseif ($sentimentScore < 0) {
                $negSum += $sentimentScore-1; // when used with math.fabs(), compensates for neutrals
            } else { 
                $neuCount++;
            }
        }
        return [$posSum, $negSum, $neuCount];
    }
    /**
     * Check whether just some words in the input are ALL CAPS
     * param list words: The words to inspect
     * returns: `True` if some but not all items in `words` are ALL CAPS
     * @param array $tokens
     * @return boolean
     */
    public function allCapDifferential(array $tokens) : bool
    {
        $upperCase = array_map('strtoupper', $tokens);
        $intersection = array_values( array_intersect( $tokens, $upperCase));        
        return count($intersection) > 0 && count($intersection) < count($tokens);                
    }
        
    /**
     * Loads the flat file data set
     * @return array
     */
    public function getLexicon() : array
    {
        if(!file_exists($this->getTxtFilePath())) {
            throw new \RuntimeException('Package vader_lexicon must be installed, php textconsole pta:install:package vader_lexicon');
        }
        
        if(empty($this->lexicon)) {
            $fh = fopen($this->getTxtFilePath(), "r");
            while (($rows[] = fgetcsv($fh, 4096, "\t")) !== FALSE);
            
            fclose($fh);
            
            $rows = array_filter($rows, function ($row) {
                return is_array($row);
            });
            
            foreach($rows as $row)
            {
                $this->lexicon[$row[0]] = $row[1];
            }            
        }
        return $this->lexicon;
    }
    
    public function boostExclamationPoints(array $tokens) : float
    {
        // check for added emphasis resulting from exclamation points (up to 4 of them)
        $freq = freq_dist($tokens);
        if ( isset( $freq->getKeyValuesByFrequency()['!'])) {
            // (empirically derived mean sentiment intensity rating increase for exclamation points)
            return min($freq->getKeyValuesByFrequency()['!'], 4) * 0.292;
        }
        return 0.0;      
    }
    
    public function neverCheck(float $valence, array $tokens, int $startIndex, int $index)
    {
        if($startIndex == 0 && $this->isNegated([$tokens[$index-1]])) {
            $valence *= self::N_SCALAR;
        } elseif($startIndex == 1) {
            if ($tokens[$index-2] == "never" &&
               ($tokens[$index-1] == "so" ||
                $tokens[$index-1] == "this")) {
                
                $valence *= 1.5;
            }
            elseif($this->isNegated([$tokens[$index-($startIndex+1)]])) {
                
                $valence *= self::N_SCALAR;
            }
        } elseif($startIndex == 2) {
            if ($tokens[$index-3] == "never" &&
               ($tokens[$index-2] == "so" || $tokens[$index-2] == "this") ||
               ($tokens[$index-1] == "so" || $tokens[$index-1] == "this")) {
                
                $valence *= 1.25;
            } elseif ($this->isNegated([$tokens[$index-($startIndex+1)]])) {
                $valence *= self::N_SCALAR;
            }
        }
        return $valence;    
    }
    
    public function boostQuestionMarks(array $tokens) : float
    {
        $freq = freq_dist($tokens);
        if ( isset( $freq->getKeyValuesByFrequency()['?'])) {
            $freqCount = $freq->getKeyValuesByFrequency()['?'];
            if( $freqCount > 1) {
                // (empirically derived mean sentiment intensity rating increase for question marks)
                if( $freqCount <= 3) {
                    return $freqCount * 0.18;
                } else {
                    return 0.96;
                }
            }
        }
        return 0.0;      
    }    
    
    
    protected function getTxtFilePath() : string
    {
        return get_storage_path('sentiment'.DIRECTORY_SEPARATOR.'vader_lexicon').DIRECTORY_SEPARATOR.'vader_lexicon.txt';
    }
    
    public function __destruct() 
    {
        unset($this->lexicon);
    }
        
}
