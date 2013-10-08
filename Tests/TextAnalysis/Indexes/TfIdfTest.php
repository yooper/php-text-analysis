<?php
namespace Tests\TextAnalysis\Indexes;

use TextAnalysis\Documents\TokensDocument;
use TextAnalysis\Collections\DocumentArrayCollection;
use TextAnalysis\Indexes\TfIdf;



/**
 * @author Dan Cardin (yooper)
 */
class TfIdfTest extends \PHPUnit_Framework_TestCase
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
        
        var_dump($tfIdf->getIdf());
        /*
        $results = $idf->query("hiking");
        $this->assertTrue($results['doc1'] > 1.21);
        $this->assertTrue($results['doc2'] > 0.4);
        $this->assertTrue($results['doc3'] == 0);         
         * 
         */
    }
    
}

