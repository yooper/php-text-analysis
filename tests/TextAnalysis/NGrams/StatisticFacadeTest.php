<?php

namespace Tests\TextAnalysis\NGrams;

use TextAnalysis\NGrams\StatisticFacade;
use TextAnalysis\Tokenizers\RegexTokenizer;
use TextAnalysis\NGrams\NGramFactory;

/**
 * Description of NGramFactoryTest
 *
 * @author yooper <yooper>
 */
class StatisticFacadeTest extends \PHPUnit\Framework\TestCase
{
    private $text;
    private $tokens;

    public function setUp()
    {
        parent::setUp();
        $this->text = file_get_contents(TEST_DATA_DIR . DS . 'Text'.DS.'Analysis'.DS.'text_ngrams.txt');
        $tokenizer = new RegexTokenizer('/([\p{L}]+[\/\-_\']?[\p{L}]+)+|[\p{L}]+/iu');        
        $this->tokens = normalize_tokens($tokenizer->tokenize($this->text));           
    }
    public function testBigrams()
    {

        $ngrams = NGramFactory::create($this->tokens, 2, '<>');

        $ngrams = NGramFactory::getFreq($ngrams, '<>');

        //test frequency
        $this->assertEquals($ngrams['know<>something'], array( 0=>2, 1=> 3, 2 => 2));

        //test tmi measure
        $ngramsStats = StatisticFacade::calculate($ngrams, 'tmi', 2);
        $this->assertEquals(round($ngramsStats['know<>something'], 4), 0.1612);

        //test ll measure
        $ngramsStats = StatisticFacade::calculate($ngrams, 'll', 2);
        $this->assertEquals(round($ngramsStats['know<>something'], 4), 13.8516);

        //test pmi measure
        $ngramsStats = StatisticFacade::calculate($ngrams, 'pmi', 2);
        $this->assertEquals(round($ngramsStats['know<>something'], 4), 4.3692);

        //test dice measure
        $ngramsStats = StatisticFacade::calculate($ngrams, 'dice', 2);
        $this->assertEquals(round($ngramsStats['know<>something'], 4), 0.8000);

        //test x2 measure
        $ngramsStats = StatisticFacade::calculate($ngrams, 'x2', 2);
        $this->assertEquals(round($ngramsStats['know<>something'], 4), 40.6444);

        //test tscore measure
        $ngramsStats = StatisticFacade::calculate($ngrams, 'tscore', 2);
        $this->assertEquals(round($ngramsStats['know<>something'], 4), 1.3458);

        //test phi measure
        $ngramsStats = StatisticFacade::calculate($ngrams, 'phi', 2);
        $this->assertEquals(round($ngramsStats['know<>something'], 4), 0.6556);

        //test odds measure
        $ngramsStats = StatisticFacade::calculate($ngrams, 'odds', 2);
        $this->assertEquals(round($ngramsStats['know<>something'], 4), 118.0000);

        //test leftFisher measure
        $ngramsStats = StatisticFacade::calculate($ngrams, 'leftFisher', 2);
        $this->assertEquals(round($ngramsStats['know<>something'], 4), 1.0000);

        //test rightFisher measure
        $ngramsStats = StatisticFacade::calculate($ngrams, 'rightFisher', 2);
        $this->assertEquals(round($ngramsStats['know<>something'], 4), 0.0016);
    }

    public function testTrigrams()
    {
        $ngrams = NGramFactory::create($this->tokens, 3, '<>');
        $ngrams = NGramFactory::getFreq($ngrams, '<>');

        //test frequency
        $this->assertEquals($ngrams['the<>know<>something'], array( 0 => 1, 1 => 4, 2 => 3, 3 => 2, 4 => 1, 5 => 1, 6 => 2));

        //test tmi measure
        $ngramsStats = StatisticFacade::calculate($ngrams, 'tmi', 3);
        $this->assertEquals(round($ngramsStats['the<>know<>something'], 4), 0.2002);

        //test ll measure
        $ngramsStats = StatisticFacade::calculate($ngrams, 'll', 3);
        $this->assertEquals(round($ngramsStats['the<>know<>something'], 4), 16.9283);
    }
}
