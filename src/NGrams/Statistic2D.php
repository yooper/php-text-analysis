<?php

namespace TextAnalysis\NGrams;

/**
 * Statistic measures for Ngrams
 * Based on The Ngram Statistics Package (Text::NSP) <http://www.d.umn.edu/~tpederse/Pubs/cicling2003-2.pdf>
 * @author Kaue Oliveira Almeida <Euak>
 */
class Statistic2D
{
    /**
    * Calculate the true mutual information value
    * @param array $ngram Array of ngrams with frequencies
    * @return float Return the calculated value
    */
    static public function tmi(array $ngram, int $totalNgrams) : float
    {
        $var = self::setStatVariables($ngram, $totalNgrams);

        $tmi = 0;

        if($var['jointFrequency']) {
            $tmi += $var['jointFrequency']/$totalNgrams * self::computePMI( $var['jointFrequency'], $var['m11'] )/ log(2);
        }

        if($var['LminusJ']) {
            $tmi += $var['LminusJ']/$totalNgrams * self::computePMI( $var['LminusJ'], $var['m12'] )/ log(2);
        }

        if($var['RminusJ']) {
            $tmi += $var['RminusJ']/$totalNgrams * self::computePMI( $var['RminusJ'], $var['m21'] )/ log(2);
        }

        if($var['n22']) {
            $tmi += $var['n22']/$totalNgrams * self::computePMI( $var['n22'], $var['m22'] )/ log(2);
        }

        return $tmi;
    }

    /**
    * Calculate the Loglikelihood coefficient
    * @param array $ngram Array of ngrams with frequencies
    * @return float Return the calculated value
    */
    static public function ll(array $ngram, int $totalNgrams) : float
    {
        $var = self::setStatVariables($ngram, $totalNgrams);

        $logLikelihood = 0;

        if($var['jointFrequency']) {
            $logLikelihood += $var['jointFrequency'] * self::computePMI ( $var['jointFrequency'], $var['m11'] );
        }

        if($var['LminusJ']) {
            $logLikelihood += $var['LminusJ'] * self::computePMI( $var['LminusJ'], $var['m12'] );
        }

        if($var['RminusJ']) {
            $logLikelihood += $var['RminusJ']  * self::computePMI( $var['RminusJ'], $var['m21'] );
        }

        if($var['n22']) {
            $logLikelihood += $var['n22'] * self::computePMI( $var['n22'], $var['m22'] );
        }

        return $logLikelihood * 2;
    }

    /**
    * Calculate the Mutual Information coefficient
    * @param array $ngram Array of ngrams with frequencies
    * @return float Return the calculated value
    */
    static public function pmi(array $ngram, int $totalNgrams) : float
    {
        $var = self::setStatVariables($ngram, $totalNgrams);

        $temp = (($var['jointFrequency'] / $var['leftFrequency'] ) / $var['rightFrequency']) * $totalNgrams;

        return(log($temp)/log(2));
    }

    /**
    * Calculate the Dice coefficient
    * @param array $ngram Array of ngrams with frequencies
    * @return float Return the calculated value
    */
    static public function dice(array $ngram, int $totalNgrams) : float
    {
        $var = self::setStatVariables($ngram, $totalNgrams);

        return (2 * $var['jointFrequency'] / ($var['leftFrequency'] + $var['rightFrequency']));
    }

    /**
    * Calculate the X squared coefficient
    * @param array $ngram Array of ngrams with frequencies
    * @return float Return the calculated value
    */
    static public function x2(array $ngram, int $totalNgrams) : float
    {
        $var = self::setStatVariables($ngram, $totalNgrams);

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
    static public function tscore(array $ngram, int $totalNgrams) : float
    {
        $var = self::setStatVariables($ngram, $totalNgrams);

        $term1 = $var['jointFrequency'] - (($var['leftFrequency'] * $var['rightFrequency'])/$totalNgrams);
        $term2 = sqrt(($var['jointFrequency']));

        return ( $term1 / $term2 );
    }

    /**
    * Calculate the Phi Coefficient
    * @param array $ngram Array of ngrams with frequencies
    * @return float Return the calculated value
    */
    static public function phi(array $ngram, int $totalNgrams) : float
    {
        $var = self::setStatVariables($ngram, $totalNgrams);

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
    static public function odds(array $ngram, int $totalNgrams) : float
    {
        $var = self::setStatVariables($ngram, $totalNgrams);

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
    * Calculate the Fisher's exact test
    * @param array $ngram Array of ngrams with frequencies
    * @return float Return the calculated value
    */
    static public function fisher(array $ngram, int $totalNgrams, string $side) : float
    {
        $var = self::setStatVariables($ngram, $totalNgrams);

        # we shall have two	arrays one for the numerator and one for the
        # denominator. the arrays will contain the factorial upper limits. we
        # shall be arrange these two arrays	in descending order. while doing the
        # actual calculation, we shall take	a numerator/denominator	pair, and
        # go from the lower	value to the higher value, in effect doing a
        # "cancellation" of	sorts.

        # first create the numerator
        $numerator = array($var['leftFrequency'], $var['rightFrequency'], $var['TminusL'], $var['TminusR']);
        arsort($numerator);

        # now to the real calculation!!!
        $probability = 0;
        $i = 0;
        $j = 0;
        # we shall calculate for n11 = 0. thereafter we shall just multiply	and
        # divide the result	for 0 with correct numbers to obtain result for	i,
        # i>0, i<=n11!! :o)

        ########### this part by Nitin O Verma

        if($side == 'left') {
            $finalLimit = $var['jointFrequency'];
            $var['jointFrequency'] = 0;
            $var['LminusJ'] = $var['leftFrequency'];
            $var['RminusJ'] = $var['rightFrequency'];
            $var['n22'] = $var['TminusL'] - $var['RminusJ'];

            while($var['n22'] < 0) {
                $var['jointFrequency']++;
                $var['LminusJ'] = $var['leftFrequency'] - $var['jointFrequency'];
                $var['RminusJ'] = $var['rightFrequency'] - $var['jointFrequency'];
                $var['n22'] = $var['TminusL'] - $var['RminusJ'];
            }

        } else {
            $finalLimit = ($var['leftFrequency'] < $var['rightFrequency']) ? $var['leftFrequency'] : $var['rightFrequency'];
        }

        ########### end of part by Nitin O Verma

        $denominator = array($totalNgrams, $var['n22'], $var['LminusJ'], $var['RminusJ'], $var['jointFrequency']);
        arsort($denominator);

        # now that we have our two arrays all nicely sorted	and in place,
        # lets do the calculations!

        $dLimits  = array();
        $nLimits  = array();
        $dIndex   = 0;
        $nIndex   = 0;

        for($j = 0; $j < 4; $j ++) {
            if ( $numerator[$j] > $denominator[$j] ) {
                $nLimits[$nIndex] =	$denominator[$j] + 1;
                $nLimits[$nIndex+1]	= $numerator[$j];
                $nIndex += 2;
            } elseif ($denominator[$j] > $numerator[$j]) {
                $dLimits[$dIndex] =	$numerator[$j] + 1;
                $dLimits[$dIndex+1]	= $denominator[$j];
                $dIndex += 2;
            }
        }

        $dLimits[$dIndex] =	1;
        $dLimits[$dIndex+1]	= $denominator[4];

        $product = 1;

        while(isset($nLimits[0])) {

            while(($product < 10000) && (isset($nLimits[0]))) {
                $product *=	$nLimits[0];
                $nLimits[0]++;
                if ( $nLimits[0] > $nLimits[1] ) {
                    array_shift($nLimits);
                    array_shift($nLimits);
                }
            }

            while($product > 1) {
                $product /=	$dLimits[0];
                $dLimits[0]++;
                if ( $dLimits[0] > $dLimits[1] ) {
                    array_shift($dLimits);
                    array_shift($dLimits);
                }
            }
        }

        while(isset($dLimits[0])) {
            $product /= $dLimits[0];
            $dLimits[0]++;
            if($dLimits[0] > $dLimits[1]) {
                array_shift($dLimits);
                array_shift($dLimits);
            }
        }

        # $product now has the hypergeometric probability for n11 =	0. add it to
        # the cumulative probability
        $probability += $product;

        # Bridget Thomson McInnes October 15 2003
        # I set i <= final_Limit rather than n11 because we want to sum the
        # hypergeometric probabilities where the count in n11 is less and or
        # equal to the observed value.

        # now for the rest of n11's	!!

        $i = ($side == 'left') ? 1 : $var['jointFrequency']+1;

        for($i; $i <= $finalLimit; $i++ ) {
            $product *= $var['LminusJ'];

            $var['n22']++;
            if($var['n22'] <= 0) {
                continue;
            }
            $product /= $var['n22'];
            $product *= $var['RminusJ'];
            $var['LminusJ']--;
            $var['RminusJ']--;
            $product /= $i;

            # thats	our new	probability for	n11 = i! :o)) cool eh? ;o))
            # add it to the	main probability! :o))
            $probability +=	$product; # !! :o)
        }

        return $probability;
    }

    /**
    * Calculate the Fisher's exact test (left-sided)
    * @param array $ngram Array of ngrams with frequencies
    * @return float Return the calculated value
    */
    static public function leftFisher(array $ngram, int $totalNgrams) : float
    {
        return self::fisher($ngram, $totalNgrams, 'left');
    }


    /**
    * Calculate the Fisher's exact test (right-sided)
    * @param array $ngram Array of ngrams with frequencies
    * @return float Return the calculated value
    */
    static public function rightFisher(array $ngram, int $totalNgrams) : float
    {
        return self::fisher($ngram, $totalNgrams, 'right');
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
    * The format is restricted: [int $jointFrequency, int $leftFrequency, int $rightFrequency]
    * @return array $var Return the array with the variables
    */
    static public function setStatVariables(array $ngram, int $totalNgrams)
    {
        $var['jointFrequency'] = $ngram[0];       # pair freq
        $var['leftFrequency']  = $ngram[1];       # single freq of first word
        $var['rightFrequency'] = $ngram[2];       # single freq of second word
        $var['LminusJ'] = $var['leftFrequency'] - $var['jointFrequency'];
        $var['RminusJ'] = $var['rightFrequency'] - $var['jointFrequency'];
        $var['TminusR'] = $totalNgrams - $var['rightFrequency'];
        $var['TminusL'] = $totalNgrams - $var['leftFrequency'];
        $var['n22'] = $var['TminusR'] - $var['LminusJ'];

        $var['m11'] = $var['leftFrequency'] * $var['rightFrequency'] / $totalNgrams;
        $var['m12'] = $var['leftFrequency'] * $var['TminusR'] / $totalNgrams;
        $var['m21'] = $var['TminusL'] * $var['rightFrequency'] / $totalNgrams;
        $var['m22'] = $var['TminusL'] * $var['TminusR'] / $totalNgrams;

        return $var;
    }
}
