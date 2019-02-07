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
    *
    * @param
    * @return
    */
    public function tmi(array $ngram) : float
    {
        $jointFrequency = $ngram[2];       # pair freq
        $leftFrequency  = $ngram[0];       # single freq of first word
        $rightFrequency = $ngram[1];       # single freq of second word
        $totalBigrams   = $this->totalBigrams;   # totalBigrams
        $LminusJ = $leftFrequency - $jointFrequency;
        $RminusJ = $rightFrequency - $jointFrequency;
        $TminusR = $totalBigrams - $rightFrequency;
        $TminusL = $totalBigrams - $leftFrequency;
        $n22 = $TminusR - $LminusJ;

        # we know totalBigrams cant be zero. so we are safe in the next 4 calculations
        $m11 = $leftFrequency * $rightFrequency / $totalBigrams;
        $m12 = $leftFrequency * $TminusR / $totalBigrams;
        $m21 = $TminusL * $rightFrequency / $totalBigrams;
        $m22 = $TminusL * $TminusR / $totalBigrams;


        $tmi = 0;

        if($jointFrequency) {
            $tmi += $jointFrequency/$totalBigrams * $this->computePMI( $jointFrequency, $m11 )/ log(2);
        }

        if($LminusJ) {
            $tmi += $LminusJ/$totalBigrams * $this->computePMI( $LminusJ, $m12 )/ log(2);
        }

        if($RminusJ) {
            $tmi += $RminusJ/$totalBigrams * $this->computePMI( $RminusJ, $m21 )/ log(2);
        }

        if($n22) {
            $tmi += $n22/$totalBigrams * $this->computePMI( $n22, $m22 )/ log(2);
        }

        return $tmi;
    }

    private function computePMI($n, $m)
    {
        $val = $n/$m;

        return log($val);
    }

    private function setTotalBigrams()
    {
        return array_sum(array_column($this->ngrams, 2));
    }

    public function calculate(string $stats)
    {
        switch ($stats) {
            case 'tmi':
                $statistics = array_map( array($this, 'tmi') , $this->ngrams);
                return $statistics;
                break;

            default:
                // code...
                break;
        }
    }
}
