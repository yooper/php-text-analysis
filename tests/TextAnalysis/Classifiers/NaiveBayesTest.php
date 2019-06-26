<?php

namespace Tests\TextAnalysis\Classifiers;

/**
 * Description of NaiveBayesTest
 *
 * @author yooper
 */
class NaiveBayesTest extends \PHPUnit\Framework\TestCase
{
    
    public function testNaiveBayes()
    {
        $nb = naive_bayes();
        $nb->train('mexican', tokenize('taco nacho enchilada burrito'));        
        $nb->train('american', tokenize('hamburger burger fries pop'));          
        $this->assertEquals(['mexican', 'american'], array_keys ( $nb->predict(tokenize('my favorite food is a burrito'))));   
        $this->assertEquals(['american', 'mexican'], array_keys ( $nb->predict(tokenize('my favorite food is pop and fries'))));                   
    }   
    
    public function testMovieReviews()
    {
        if( getenv('SKIP_TEST')) {
            return;
        }            
        try {
            get_storage_path('corpora/movie_reviews');
        } catch(\Exception $ex) {
            return;
        }
        
        $posFilePaths = scan_dir(get_storage_path('corpora/movie_reviews/pos'));        
        $nb = naive_bayes();
        
        foreach($posFilePaths as $filePath)
        {
            $nb->train('positive', $this->getTokenizedReviews($filePath));            
        }

        $negFilePaths = scan_dir(get_storage_path('corpora/movie_reviews/neg'));                
        foreach($negFilePaths as $filePath)
        {
            $nb->train('negative', $this->getTokenizedReviews($filePath));            
        } 
        
        $movieReviewTokens = tokenize($this->getMovieReview());
        $stopWords = get_stop_words(VENDOR_DIR."yooper/stop-words/data/stop-words_english_1_en.txt");
        filter_stopwords($movieReviewTokens, $stopWords);
        filter_tokens($movieReviewTokens, 'PunctuationFilter');
        filter_tokens($movieReviewTokens, 'QuotesFilter');
        $movieReviewTokens = stem($movieReviewTokens);                   
        $this->assertEquals('positive', array_keys($nb->predict($movieReviewTokens))[0]);
        
    }
    
    protected function getTokenizedReviews(string $filePath) : array
    {
        static $stopWords = null;
        
        if(!$stopWords) {
            $stopWords = get_stop_words(VENDOR_DIR."yooper/stop-words/data/stop-words_english_1_en.txt");
        }
        
        $tokens = tokenize(file_get_contents($filePath));
        filter_tokens($tokens, 'PunctuationFilter');
        filter_tokens($tokens, 'QuotesFilter');
        filter_stopwords($tokens, $stopWords);        
        $tokens = stem($tokens);
        $tokens = filter_empty($tokens);
        return $tokens;
    }
    
    /**
     * Taken from https://www.rollingstone.com/movies/reviews/incredibles-2-movie-review-pixar-w521419
     * @return string
     */
    protected function getMovieReview() : string
    {
        return <<<TEXT
It really is incredible. Yes, the sequel to Brad Bird's 2004 classic is not the groundbreaker that stormed the multiplex 14 years ago – you only get to be shiny new once. Pixar's animated miracle didn't look or sound like anything else, being about a family of superheroes forced into retirement by a legal system that didn't care for the collateral damage caused by their do-gooding antics. How many family films dealt with midlife crisis, marital dysfunction, child neglect, impotence fears, fashion faux pas and existential angst? But this follow-up is every bit the start-to-finish sensation as the original, and you'll be happy to know that Bird's subversive spirit is alive and thriving. The kiddies probably won't notice – they'll be too distracted by all the whooshing derring-do – but like its Oscar-winning predecessor, The Incredibles 2 doesn't ring cartoonish. It rings true.

RELATED
 
25 Best Pixar Movie Characters
From Buzz Lightyear to Bing Bong, the most memorable heroes and villains from groundbreaking animation giants

It may have taken years for Bird & co. to get this sequel together, but the action picks up right where the original left off, as if it were yesterday. The Parr family – mom Helen (voiced by Holly Hunter), dad Bob (Craig T. Nelson), 14-year-old Violet (Sarah Vowell), 10-year-old Dash (Huckleberry Milner) and baby Jack-Jack (Eli Fucile) – is still in exile, massively frustrated by being forced to keep their powers in check. All of which goes out the window when a villain named the Underminer (John Ratzenberger) starts raising hell in Municiberg. Nothing like heroics to get the family out of its funk.

But there's a difference this time. Helen, a.k.a. Elastigirl, takes charge, leaving Mr. Incredible to stay home with his teen daughter and the tots. Female empowerment suits Mom, as she stops a runaway train from wreaking havoc thanks to her quick thinking and stretchable arms. It's a great action sequence – and some great voicework from Hunter, whose vocals can stretch from subtle to pow and all stops in between. This is her show and she makes the newly emboldened character resonate onscreen like nobody's business.

Elastigirl clearly likes getting costumed up again and back into the thick of it. She's not alone: Telecommunications tycoon Winston Deavor (Bob Odenkirk) thinks the time is now to get the Incredibles back into the public's good graces. With the help of his tech-nerd sister Evelyn (Catherine Keener – talk about great voices!), he launches a campaign to make superheroes popular again. Dad is stuck playing Mr. Mom at home, totally unable to cope with Violet's boy problems, Dash's adolescent rebellion and a baby who'sshowing power-potential that's both deeply funny and scary. Jack-Jack's transformation is a riot. It's the cue for the return of fashion guru Edna Mode (again voiced hilariously by Bird) to take the demon baby in hand for a supersuit fitting-slash-overnight sleepover. It's a wildly comic duel of scenestealers.

The villain of the piece is a diabolically clever entity named Screenslaver, which seeks to control the minds of citizens through screens – not a hard job, since damn near everyone is already enslaved to the screens on their devices. Unlike other filmmakers who are bound to formulas and black-and-white conceptions of heroes and villains, Bird works outside the box. The bad guy wants to destroy the Incredibles because citizens would rather have fantasy figures save the world instead of getting off their lazy asses and doing something about it themselves. In other words, Screenslaver – despite using nefarious means to an end – has a point. And the film is richer for the character's ambiguity.

Of course, nothing stops the fun, set to another rousing score by the ever-fantastic Michael Giacchino. All the stops are pulled out in the rousing climax that brings the characters together, including family friend Lucius Best/Frozone (Samuel L. Jackson). The setting is a mega-yacht, owned by the Deavors, where Screenslaver maneuvers to turn our heroes to the dark side. No spoilers, except to say that Bird is peerless at playing with our feelings while never veering from his heartfelt tribute to the Parrs as the core resilient American family. The Incredibles 2 is more than peak summer entertainment. It's an exhilarating gift.        
        
TEXT;
    }
    
    
    
}
