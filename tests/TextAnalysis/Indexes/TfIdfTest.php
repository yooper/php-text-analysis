<?php
namespace Tests\TextAnalysis\Indexes;

use TextAnalysis\Documents\TokensDocument;
use TextAnalysis\Collections\DocumentArrayCollection;
use TextAnalysis\Indexes\TfIdf;



/**
 * @author yooper (yooper)
 */
class TfIdfTest extends \PHPUnit\Framework\TestCase
{
        
    public function testIdf()
    {
        $docs = array(
            new TokensDocument(array("marquette", "michigan", "hiking", "hiking", "hiking" , "camping", "swimming")),
            new TokensDocument(array("ironwood", "michigan", "hiking", "biking", "camping", "swimming","marquette")),
            new TokensDocument(array("no","tokens"))
        );
        
        $docCollection = new DocumentArrayCollection($docs);
           
        $tfIdf = new TfIdf($docCollection);
        
        
    }
    
}

