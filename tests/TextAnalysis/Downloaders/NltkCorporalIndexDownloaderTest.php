<?php

namespace Tests\TextAnalysis\Downloaders;


use TextAnalysis\Downloaders\NltkCorporaIndexDownloader;
use Mockery;


/**
 *
 * @author yooper
 */
class NltkCorporalIndexDownloaderTest extends \PHPUnit\Framework\TestCase
{
    public function testDownloaderXml()
    {
        
        $mock = Mockery::mock("TextAnalysis\Downloaders\NltkCorporaIndexDownloader[getXmlContent]")
                ->shouldAllowMockingProtectedMethods();
        
        $mock->shouldReceive('getXmlContent')
                ->andReturn(simplexml_load_string($this->getXmlContent()));
        
        $packages = $mock->getPackages();
        $this->assertCount(2, $packages);
        $this->assertEquals('maxent_ne_chunker', $packages[0]->getId());
        $this->assertEquals('abc', $packages[1]->getId());                        
    }
    
    /**
     * 
     * @return string
     */
    public function getXmlContent()
    {
        return <<<XML
<?xml version="1.0"?>
<?xml-stylesheet href="index.xsl" type="text/xsl"?>
<nltk_data>
  <packages>
    <package checksum="d577c2cd0fdae148b36d046b14eb48e6" id="maxent_ne_chunker" languages="English" name="ACE Named Entity Chunker (Maximum entropy)" size="13404747" subdir="chunkers" unzip="1" unzipped_size="23604982" url="https://raw.githubusercontent.com/nltk/nltk_data/gh-pages/packages/chunkers/maxent_ne_chunker.zip" />
    <package author="Australian Broadcasting Commission" checksum="ffb36b67ff24cbf7daaf171c897eb904" id="abc" name="Australian Broadcasting Commission 2006" size="1487851" subdir="corpora" unzip="1" unzipped_size="4054966" url="https://raw.githubusercontent.com/nltk/nltk_data/gh-pages/packages/corpora/abc.zip" webpage="http://www.abc.net.au/" />
  </packages>
  <collections>
    <collection id="all-corpora" name="All the corpora">
      <item ref="abc" />
      <item ref="alpino" />
    </collection>
    <collection id="all" name="All packages">
      <item ref="abc" />
      <item ref="alpino" />
    </collection>
    <collection id="book" name="Everything used in the NLTK Book">
      <item ref="abc" />
      <item ref="brown" />
    </collection>
  </collections>
</nltk_data>          
        
XML;
        
    }
}
