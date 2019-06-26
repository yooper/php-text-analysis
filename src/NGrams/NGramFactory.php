<?php

namespace TextAnalysis\NGrams;

/**
 * Generate ngrams
 *
 * @author yooper <yooper>
 */
class NGramFactory
{
    const BIGRAM = 2;
    const TRIGRAM = 3;

    /**
    * Protect the constructor
    */
    protected function __construct(){}

    /**
    * Generate Ngrams from the tokens
    * @param array $tokens
    * @param int $nGramSize
    * @return  array return an array of the ngrams
    */
    static public function create(array $tokens, $nGramSize = self::BIGRAM, $separator = ' ') : array
    {
        $length = count($tokens) - $nGramSize + 1;
        if($length < 1) {
            return [];
        }
        $ngrams = array_fill(0, $length, ''); // initialize the array

        for($index = 0; $index < $length; $index++)
        {
            for($jindex = 0; $jindex < $nGramSize; $jindex++)
            {
                $ngrams[$index] .= $tokens[$index + $jindex];
                //alterado a condição, pois não considera-se o tamanho do separador e sim a posição do ponteiro em relação ao tamanho do Ngram
                if($jindex < $nGramSize - 1) {
                    $ngrams[$index] .= $separator;
                }
            }
        }
       return $ngrams;
    }

    /**
    * Set the frenquecies of the ngrams and their respective tokens
    * @param array $ngrams
    * @param string $sep
    * @return array return an array of the ngrams with frequencies
    */
    static public function getFreq(array $ngrams, string $sep = ' ') : array
    {
        //getting the frequencies of the ngrams array and an array with no repetition
        $ngramsUnique = array_count_values($ngrams);

        //array to be the product of this function
        $ngramsFinal = array();

        //creates an array of tokens per ngram
        $ngramsArray = self::ngramsAsArray($sep, $ngrams);

        $ngramSize = count($ngramsArray[0]);

        $tokens_frequencies = self::readFreq($sep, $ngrams);
        $combo_frequencies  = self::readCombFreq($sep, $ngrams);

        //interate the array with no repeated ngrams
        foreach ($ngramsUnique as $ngramString => $ngramFrequency) {
            $ngramsFinal[$ngramString] = array($ngramFrequency); //putting into the final array an array of frequencies (first, the ngram frequency)

            $ngramArray = explode($sep, $ngramString); //getting an array of tokens of the ngram
            foreach ($ngramArray as $kToken => $token) { //iterating the array of tokens of the ngram
                $ngramsFinal[$ngramString][$kToken+1] = $tokens_frequencies[$token][$kToken]; //getting the frequency of the token

                if($ngramSize > 2) {
                    //getting the combined frequency of the tokens
                    for ($i = $kToken+1; $i < $ngramSize; $i++) {
                        $ngramsFinal[$ngramString][$ngramSize+$kToken+$i] = $combo_frequencies[$token.$sep.$ngramArray[$i]][$kToken][$i];
                    }
                }
            }

        }

        return $ngramsFinal;
    }

    /**
    * Counts the frequency of each token of an ngram array
    * @param string $sep
    * @param array $ngrams
    * @return array $frequencies Return an array of tokens with its frequencies by its positions
    */
    static public function readFreq(string $sep, array $ngrams) : array
    {
        $ngrams = self::ngramsAsArray($sep, $ngrams);
        $frequencies = array();
        foreach ($ngrams as $ngram) {
            foreach ($ngram as $pos => $token) {
                if(isset($frequencies[$token][$pos])) { //checks if the token in that position was already counted
                    $frequencies[$token][$pos] += 1;
                } else {
                    $frequencies[$token][$pos] = 1;
                }
            }
        }
        return $frequencies;
    }

    /**
    * Counts the frequency of combo of tokens of an ngram array
    * @param string $sep
    * @param array $ngrams
    * @return array $frequencies Return an array of a combo of tokens with its frequencies by its positions
    */
    static public function readCombFreq(string $sep, array $ngrams) : array
    {
        $ngrams = self::ngramsAsArray($sep, $ngrams);
        $frequencies = array();
        foreach ($ngrams as $ngram) {
            foreach ($ngram as $posToken => $token) {
                for ($i = $posToken+1; $i < count($ngram); $i++) {
                    if(isset($frequencies[$token.$sep.$ngram[$i]][$posToken][$i])) { //checks if the combo already exists
                        $frequencies[$token.$sep.$ngram[$i]][$posToken][$i] += 1;
                    } else {
                        $frequencies[$token.$sep.$ngram[$i]][$posToken][$i] = 1;
                    }
                }
            }

        }
        return $frequencies;
    }

    /**
    * Transform the ngram array to an array of their tokens
    * @param string $sep
    * @param array $ngrams
    * @return array $ngramsArray
    */
    static private function ngramsAsArray(string $sep, array $ngrams) : array {
        $ngramsArray = array();
        foreach($ngrams as $key => $ngram) {
            $ngramsArray[] = explode($sep, $ngram);
        }
        return $ngramsArray;
    }
}
