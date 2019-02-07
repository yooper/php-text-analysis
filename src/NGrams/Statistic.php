<?php

namespace TextAnalysis\NGrams;

/**
 *
 *
 * @author Kaue <Euak>
 */
class Statistic
{
    private $ngrams;
    private $totalBigrams;

    /**
    * Protect the constructor
    */
    public function __construct(array $ngrams)
    {
        $this->ngrams = $ngrams;
        $this->totalBigrams = $this->setTotalBigrams();
    }

    /**
    * Calculate the true mutual information value
    * @param array $ngram Array of ngrams with frequencies
    * @return float Return the calculated value
    */
    public function tmi(array $ngram) : float
    {
        $var = $this->setStatVariables($ngram);

        $tmi = 0;

        if($var['jointFrequency']) {
            $tmi += $var['jointFrequency']/$this->totalBigrams * $this->computePMI( $var['jointFrequency'], $var['m11'] )/ log(2);
        }

        if($var['LminusJ']) {
            $tmi += $var['LminusJ']/$this->totalBigrams * $this->computePMI( $var['LminusJ'], $var['m12'] )/ log(2);
        }

        if($var['RminusJ']) {
            $tmi += $var['RminusJ']/$this->totalBigrams * $this->computePMI( $var['RminusJ'], $var['m21'] )/ log(2);
        }

        if($var['n22']) {
            $tmi += $var['n22']/$this->totalBigrams * $this->computePMI( $var['n22'], $var['m22'] )/ log(2);
        }

        return $tmi;
    }

    /**
    * Calculate the Loglikelihood coefficient
    * @param array $ngram Array of ngrams with frequencies
    * @return float Return the calculated value
    */
    public function ll(array $ngram) : float
    {
        $var = $this->setStatVariables($ngram);

        $logLikelihood = 0;

        if($var['jointFrequency']) {
            $logLikelihood += $var['jointFrequency'] * $this->computePMI ( $var['jointFrequency'], $var['m11'] );
        }

        if($var['LminusJ']) {
            $logLikelihood += $var['LminusJ'] * $this->computePMI( $var['LminusJ'], $var['m12'] );
        }

        if($var['RminusJ']) {
            $logLikelihood += $var['RminusJ']  * $this->computePMI( $var['RminusJ'], $var['m21'] );
        }

        if($var['n22']) {
            $logLikelihood += $var['n22'] * $this->computePMI( $var['n22'], $var['m22'] );
        }

        return $logLikelihood * 2;
    }

    /**
    * Calculate the Mutual Information coefficient
    * @param array $ngram Array of ngrams with frequencies
    * @return float Return the calculated value
    */
    public function pmi(array $ngram) : float
    {
        $var = $this->setStatVariables($ngram);

        $temp = (($var['jointFrequency'] / $var['leftFrequency'] ) / $var['rightFrequency']) * $this->totalBigrams;

        return(log($temp)/log(2));
    }

    /**
    * Calculate the Dice coefficient
    * @param array $ngram Array of ngrams with frequencies
    * @return float Return the calculated value
    */
    public function dice(array $ngram) : float
    {
        $var = $this->setStatVariables($ngram);

        return (2 * $var['jointFrequency'] / ($var['leftFrequency'] + $var['rightFrequency']));
    }

    /**
    * Calculate the X squared coefficient
    * @param array $ngram Array of ngrams with frequencies
    * @return float Return the calculated value
    */
    public function x2(array $ngram) : float
    {
        $var = $this->setStatVariables($ngram);

        $Xsquare = 0;

        $Xsquare += ( ( $var['jointFrequency'] - $var['m11'] ) ** 2 ) / $var['m11'];
        $Xsquare += ( ( $var['LminusJ'] - $var['m12'] ) ** 2 ) / $var['m12'];
        $Xsquare += ( ( $var['RminusJ'] - $var['m21'] ) ** 2 ) / $var['m21'];
        $Xsquare += ( ( $var['n22'] - $var['m22'] ) ** 2 ) / $var['m22'];

        return $Xsquare;
    }

    /**
    * Calculate the T-score
    * @param array $ngram Array of ngrams with frequencies
    * @return float Return the calculated value
    */
    public function tscore(array $ngram) : float
    {
        $var = $this->setStatVariables($ngram);

        $term1 = $var['jointFrequency'] - (($var['leftFrequency'] * $var['rightFrequency'])/$this->totalBigrams);
        $term2 = sqrt(($var['jointFrequency']));

        return ( $term1 / $term2 );
    }

    /**
    * Calculate the Phi Coefficient
    * @param array $ngram Array of ngrams with frequencies
    * @return float Return the calculated value
    */
    public function phi(array $ngram) : float
    {
        $var = $this->setStatVariables($ngram);

        $term1 = $var['jointFrequency'] * $var['n22'] - $var['RminusJ'] * $var['LminusJ'];
        $term2 = $var['leftFrequency'] * $var['rightFrequency'] * $var['TminusR'] * $var['TminusL'];

        $phi = ($term1 * $term1)/$term2;

        return $phi;
    }

    /**
    * Calculate the Odds Ratio
    * @param array $ngram Array of ngrams with frequencies
    * @return float Return the calculated value
    */
    public function odds(array $ngram) : float
    {
        $var = $this->setStatVariables($ngram);

        if ($var['RminusJ'] == 0) {
    	    $var['RminusJ'] = 1;
        }

        if ($var['LminusJ'] == 0) {
    	    $var['LminusJ'] = 1;
        }

        $term1 = $var['jointFrequency'] * $var['n22'];
        $term2 = $var['RminusJ'] * $var['LminusJ'];

        $odds = $term1/$term2;

        return $odds;
    }

    /**
    * Calculate the Pointwise mutual information
    * @param int $n
    * @param int $m
    * @return float Return the calculated value
    */
    private function computePMI($n, $m)
    {
        $val = $n/$m;

        return log($val);
    }

    /**
    * Sets the total number of bigrams
    */
    private function setTotalBigrams()
    {
        return array_sum(array_column($this->ngrams, 2));
    }

    /**
    * Sets variables to calculate the statistic measures
    * @return array $var Return the array with the variables
    */
    public function setStatVariables(array $ngram)
    {
        $var['jointFrequency'] = $ngram[2];       # pair freq
        $var['leftFrequency']  = $ngram[0];       # single freq of first word
        $var['rightFrequency'] = $ngram[1];       # single freq of second word
        $var['LminusJ'] = $var['leftFrequency'] - $var['jointFrequency'];
        $var['RminusJ'] = $var['rightFrequency'] - $var['jointFrequency'];
        $var['TminusR'] = $this->totalBigrams - $var['rightFrequency'];
        $var['TminusL'] = $this->totalBigrams - $var['leftFrequency'];
        $var['n22'] = $var['TminusR'] - $var['LminusJ'];

        $var['m11'] = $var['leftFrequency'] * $var['rightFrequency'] / $this->totalBigrams;
        $var['m12'] = $var['leftFrequency'] * $var['TminusR'] / $this->totalBigrams;
        $var['m21'] = $var['TminusL'] * $var['rightFrequency'] / $this->totalBigrams;
        $var['m22'] = $var['TminusL'] * $var['TminusR'] / $this->totalBigrams;

        return $var;
    }

    /**
    * Calculate the statistic for whole ngram array
    * @param string $stats Name of the statistic measure
    * @return float Return the ngram array with the statistic values
    */
    public function calculate(string $stats)
    {
        return array_map( array($this, $stats) , $this->ngrams);
    }
}
