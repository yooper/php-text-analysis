<?php

namespace TextAnalysis\NGrams;

/**
 * Statistic measures for Ngrams
 * Based on The Ngram Statistics Package (Text::NSP) <http://www.d.umn.edu/~tpederse/Pubs/cicling2003-2.pdf>
 * @author Kaue Oliveira Almeida <Euak>
 */
class Statistic3D
{
    /**
    * Calculate the true mutual information value for trigrams
    * @param array $ngram Array of ngrams with frequencies
    * @return float Return the calculated value
    */
    static public function tmi(array $ngram, int $totalNgrams) : float
    {
        $var = self::setStatVariables($ngram, $totalNgrams);


        $tmi3 = 0;

        if($var['jointFrequency']) {
            $tmi3 += $var['jointFrequency']/$totalNgrams * self::computePMI( $var['jointFrequency'], $var['e111'] )/ log(2);
        }

        if($var['n112']) {
            $tmi3 += $var['n112']/$totalNgrams * self::computePMI( $var['n112'], $var['e112'] )/ log(2);
        }

        if($var['n121']) {
            $tmi3 += $var['n121']/$totalNgrams * self::computePMI( $var['n121'], $var['e121'] )/ log(2);
        }


        if($var['n122']) {
            $tmi3 += $var['n122']/$totalNgrams * self::computePMI( $var['n122'], $var['e122'] )/ log(2);
        }

        if($var['n211']) {
            $tmi3 += $var['n211']/$totalNgrams * self::computePMI( $var['n211'], $var['e211'] )/ log(2);
        }

        if($var['n212']) {
            $tmi3 += $var['n212']/$totalNgrams * self::computePMI( $var['n212'], $var['e212'] )/ log(2);
        }

        if($var['n221']) {
            $tmi3 += $var['n221']/$totalNgrams * self::computePMI( $var['n221'], $var['e221'] )/ log(2);
        }

        if($var['n222']) {
            $tmi3 += $var['n222']/$totalNgrams * self::computePMI( $var['n222'], $var['e222'] )/ log(2);
        }

        return $tmi3;
    }

    /**
    * Calculate the Loglikelihood coefficient for trigrams
    * @param array $ngram Array of ngrams with frequencies
    * @return float Return the calculated value
    */
    static public function ll(array $ngram, int $totalNgrams) : float
    {
        $var = self::setStatVariables($ngram, $totalNgrams);

        $logLikelihood = 0;

        if($var['jointFrequency']) {
            $logLikelihood += $var['jointFrequency'] * self::computePMI ( $var['jointFrequency'], $var['e111'] );
        }

        if($var['n112']) {
            $logLikelihood += $var['n112'] * self::computePMI( $var['n112'], $var['e112'] );
        }

        if($var['n121']) {
            $logLikelihood += $var['n121']  * self::computePMI( $var['n121'], $var['e121'] );
        }

        if($var['n122']) {
            $logLikelihood += $var['n122'] * self::computePMI( $var['n122'], $var['e122'] );
        }

        if($var['n211']) {
            $logLikelihood += $var['n211'] * self::computePMI( $var['n211'], $var['e211'] );
        }

        if($var['n212']) {
            $logLikelihood += $var['n212'] * self::computePMI( $var['n212'], $var['e212'] );
        }

        if($var['n221']) {
            $logLikelihood += $var['n221'] * self::computePMI( $var['n221'], $var['e221'] );
        }

        if($var['n222']) {
            $logLikelihood += $var['n222'] * self::computePMI( $var['n222'], $var['e222'] );
        }

        return ($logLikelihood * 2);
    }
    /**
    * Calculate the Pointwise mutual information
    * @param int $n
    * @param int $m
    * @return float Return the calculated value
    */
    static public function computePMI($n, $m)
    {
        $val = $n/$m;

        return log($val);
    }

    /**
    * Sets variables to calculate the statistic measures
    * @return array $var Return the array with the variables
    */
    static public function setStatVariables(array $ngram, int $totalNgrams)
    {
        $var['jointFrequency']          = $ngram[0];       # n111 whole trigram freq
        $var['firstFrequency']          = $ngram[1];       # n1pp single freq of first word
        $var['secondFrequency']         = $ngram[2];       # np1p single freq of second word
        $var['thirdFrequency']          = $ngram[3];       # npp1 single freq of third word
        $var['firstSecondFrequency']   = $ngram[4];       # n11p freq of first with the second word
        $var['firstThirdFrequency']    = $ngram[5];       # n1p1 freq of second with the third word
        $var['secondThirdFrequency']   = $ngram[6];       # np11 freq of first with the third word

        $var['n112'] = $var['firstSecondFrequency'] - $var['jointFrequency'];
        $var['n211'] = $var['secondThirdFrequency'] - $var['jointFrequency'];
        $var['n212'] = $var['secondFrequency'] - $var['jointFrequency'] - $var['n112'] - $var['n211'];

	    $var['n121'] = $var['firstThirdFrequency'] - $var['jointFrequency'];
        $var['n122'] = $var['firstFrequency'] - $var['jointFrequency'] - $var['n112'] - $var['n121'];
        $var['n221'] = $var['thirdFrequency'] - $var['jointFrequency'] - $var['n211'] - $var['n121'];
        $var['nppp'] = $totalNgrams;
	    $var['n222'] = $var['nppp'] - ($var['jointFrequency'] + $var['n112'] + $var['n121'] + $var['n122'] + $var['n211'] + $var['n212'] + $var['n221']);
    	$var['n2pp'] = $var['nppp'] - $var['firstFrequency'];
        $var['np2p'] = $var['nppp'] - $var['secondFrequency'];
        $var['npp2'] = $var['nppp'] - $var['thirdFrequency'];

        $var['e111'] = $var['firstFrequency'] * $var['secondFrequency'] * $var['thirdFrequency']/($var['nppp']**2);
        $var['e112'] = $var['firstFrequency'] * $var['secondFrequency'] * $var['npp2']/($var['nppp']**2);
        $var['e121'] = $var['firstFrequency'] * $var['np2p'] * $var['thirdFrequency']/($var['nppp']**2);
        $var['e122'] = $var['firstFrequency'] * $var['np2p'] * $var['npp2']/($var['nppp']**2);
        $var['e211'] = $var['n2pp'] * $var['secondFrequency'] * $var['thirdFrequency']/($var['nppp']**2);
        $var['e212'] = $var['n2pp'] * $var['secondFrequency'] * $var['npp2']/($var['nppp']**2);
        $var['e221'] = $var['n2pp'] * $var['np2p'] * $var['thirdFrequency']/($var['nppp']**2);
        $var['e222'] = $var['n2pp'] * $var['np2p'] * $var['npp2']/($var['nppp']**2);

        return $var;
    }
}
