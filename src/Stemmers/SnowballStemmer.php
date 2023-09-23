<?php
declare(strict_types = 1);

namespace TextAnalysis\Stemmers;

use TextAnalysis\Interfaces\IStemmer;
use Wamania\Snowball\StemmerFactory;


/**
 * A wrapper around PHP native snowball implementation
 * @author yooper
 */
class SnowballStemmer implements IStemmer
{
    const BASE_NAMESPACE = '\\Wamania\\Snowball\\';
    
    /**
     *
     * @var \Wamania\Snowball\Stem
     */
    protected $stemmer;

    /**
     * @throws \Wamania\Snowball\NotFoundException
     */
    public function __construct(string $stemmerType = 'English')
    {
        $version = (int)\Composer\InstalledVersions::getVersion('wamania/php-stemmer')[0];
        if($version === 1){
            $className = self::BASE_NAMESPACE.$stemmerType;
            if(!class_exists($className)) {
                throw new \RuntimeException("Class {$stemmerType} does not exist");
            }
            $this->stemmer = new $className();
        }
        // support version 2 and above
        else {
            $this->stemmer = StemmerFactory::create (strtolower($stemmerType));
        }
    }

    public function stem($token) : string
    {
        return $this->stemmer->stem($token);
    }

}