<?php

namespace Tests\TextAnalysis\Analysis\Summarize;

/**
 * Test out the simple summary algorithm
 * @author yooper
 */
class SimpleTest extends \PHPUnit\Framework\TestCase
{
    public function testSimpleWithStopwords()
    {
        $stopwords = get_stop_words(VENDOR_DIR."yooper/stop-words/data/stop-words_english_1_en.txt"); 
        $stopwords = array_map(function($word){ return " {$word} ";}, $stopwords);
        $bestSentences = summary_simple($this->getArticle(), $stopwords);     
        $this->assertCount(13, $bestSentences);
        $this->assertEquals($this->getTopSentence(), $bestSentences[0]);
                      
    }
    
    public function testSimpleWithoutStopwords()
    {
        $bestSentences = summary_simple($this->getArticle());     
        $this->assertCount(13, $bestSentences);
        $this->assertNotEquals($this->getTopSentenceWithoutStopwords(), $bestSentences[0]);              
    }    
    
    public function getArticle() : string
    {
        return <<<TEXT
According to a Tuesday news release, Houghton County leaders are asking for a slowing of supply donations.

Volunteers and financial donations are still needed, along with dehumidifiers, box fans or large equipment that can be used for excavating, demolition or reconstruction.

"The response to our recent flood disaster has been overwhelming, and the Copper Country cannot be thankful enough for the support that’s been received," said Michael Babcock, the director of marketing and communications at Finlandia University. "However, as of now, volunteers have reached a point where enough normal supplies are on hand. Officials are now asking that the flow of general donations be reduced or stopped with a few exceptions. We know of several additional semi loads coming, but we’re now asking that any additional large deliveries that are planned please be put on hold, unless the items being donated are dehumidifiers, box fans or large equipment that can be used for excavating, demolition or reconstruction."

Volunteers are still needed. The recovery effort is transitioning from initial clean-up to the rehab and reconstruction phase, and additional volunteers are a vital part of that effort.

To donate money, please go to coppercountrystrong.com/donate.

On Friday at 4 p.m. the Flood Relief Supply Distribution at Dee Stadium will be closing. Those in need of supplies are asked to stop by before it closes to get what’s needed for the weekend. Next steps for the distribution center are being evaluated and will be announced as soon as possible.        
TEXT;
    }
    
    public function getTopSentence()
    {
        return '"the response to our recent flood disaster has been overwhelming, and the copper country cannot be thankful enough for the support that\'s been received," said michael babcock, the director of marketing and communications at finlandia university.';
    }
    
    public function getTopSentenceWithoutStopwords()
    {
        return 'we know of several additional semi loads coming, but we’re now asking that any additional large deliveries that are planned please be put on hold, unless the items being donated are dehumidifiers, box fans or large equipment that can be used for excavating, demolition or reconstruction."';
    }
}
