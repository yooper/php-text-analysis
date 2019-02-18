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
        $separatorLength = strlen($separator);
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
        $ngrams_unique = array_count_values($ngrams);

        //array to be the product of this function
        $ngrams_final = array();

        //creates an array of tokens per ngram
        $ngrams_arry = self::ngramsAsArray($sep, $ngrams);

        //interate the array with no repeated ngrams
        foreach ($ngrams_unique as $ngram_string => $ngram_frequency) {
            $ngrams_final[$ngram_string] = array($ngram_frequency); //putting into the final array an array of frequencies (first, the ngram frequency)

            $ngram_array = explode($sep, $ngram_string); //getting an array of tokens of the ngram
            $ngram_size = count($ngram_array); //getting the size of ngram
            foreach ($ngram_array as $k_token => $token) { //iterating the array of tokens of the ngram
                $ngrams_final[$ngram_string][$k_token+1] = self::countFreq($ngrams_arry, $token, $k_token); //getting the frequency of the token

                if($ngram_size > 2) {
                    //getting the combined frequency of the tokens
                    for ($i = $k_token+1; $i < $ngram_size; $i++) {
                        $ngrams_final[$ngram_string][$ngram_size+$k_token+$i] = self::countFreq($ngrams_arry, $token, $k_token, $ngram_array[$i], $i);
                    }
                }
            }

        }

        return $ngrams_final;
    }

    /**
    * Count the number of times the given string(s) to the given position(s) occurs in the given ngrams array.
    * @param array $ngrams_arry
    * @param string $str1
    * @param int $pos1
    * @param string $str2
    * @param int $pos2
    * @return int $count return the frequency
    */
    static private function countFreq(array $ngrams_arry, string $str1, int $pos1, string $str2 = null, int $pos2 = null) : int
    {
        $count = 0;

        //counts the number of times the given string(s) to the given position(s) occurs in the given ngrams array.
        foreach ($ngrams_arry as $ngram_array) {
            if($str1 === $ngram_array[$pos1]) {
                if(isset($str2) && isset($pos2)) {
                    if($str2 === $ngram_array[$pos2]) {
                        $count++;
                    }
                } else {
                    $count++;
                }
            }
        }

        return $count;
    }

    /**
    * Transform the ngram array to an array of their tokens
    * @param string $sep
    * @param array $ngrams
    * @return array $ngrams_arry
    */
    static private function ngramsAsArray(string $sep, array $ngrams) : array {
        $ngrams_arry = array();
        foreach($ngrams as $key => $ngram) {
            $ngrams_arry[] = explode($sep, $ngram);
        }
        return $ngrams_arry;
    }
}
