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
        $ngramsUnique = array_count_values($ngrams);

        //array to be the product of this function
        $ngramsFinal = array();

        //creates an array of tokens per ngram
        $ngramsArray = self::ngramsAsArray($sep, $ngrams);

        //interate the array with no repeated ngrams
        foreach ($ngramsUnique as $ngramString => $ngramFrequency) {
            $ngramsFinal[$ngramString] = array($ngramFrequency); //putting into the final array an array of frequencies (first, the ngram frequency)

            $ngramArray = explode($sep, $ngramString); //getting an array of tokens of the ngram
            $ngramSize = count($ngramArray); //getting the size of ngram
            foreach ($ngramArray as $kToken => $token) { //iterating the array of tokens of the ngram
                $ngramsFinal[$ngramString][$kToken+1] = self::countFreq($ngramsArray, $token, $kToken); //getting the frequency of the token

                if($ngramSize > 2) {
                    //getting the combined frequency of the tokens
                    for ($i = $kToken+1; $i < $ngramSize; $i++) {
                        $ngramsFinal[$ngramString][$ngramSize+$kToken+$i] = self::countFreq($ngramsArray, $token, $kToken, $ngramArray[$i], $i);
                    }
                }
            }

        }

        return $ngramsFinal;
    }

    /**
    * Count the number of times the given string(s) to the given position(s) occurs in the given ngrams array.
    * @param array $ngramsArray
    * @param string $str1
    * @param int $pos1
    * @param string $str2
    * @param int $pos2
    * @return int $count return the frequency
    */
    static private function countFreq(array $ngramsArray, string $str1, int $pos1, string $str2 = null, int $pos2 = null) : int
    {
        $count = 0;

        //counts the number of times the given string(s) to the given position(s) occurs in the given ngrams array.
        foreach ($ngramsArray as $ngramArray) {
            if($str1 === $ngramArray[$pos1]) {
                if(isset($str2) && isset($pos2)) {
                    if($str2 === $ngramArray[$pos2]) {
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
