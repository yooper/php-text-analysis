php-text-analysis
=============
![alt text](https://travis-ci.org/yooper/php-text-analysis.svg?branch=master "Build status")

[![Latest Stable Version](https://poser.pugx.org/yooper/php-text-analysis/v/stable)](https://packagist.org/packages/yooper/php-text-analysis)

[![Total Downloads](https://poser.pugx.org/yooper/php-text-analysis/downloads)](https://packagist.org/packages/yooper/php-text-analysis)

PHP Text Analysis is a library for performing Information Retrieval (IR) and Natural Language Processing (NLP) tasks using the PHP language. 
There are tools in this library that can perform:

* document classification
* sentiment analysis
* compare documents
* frequency analysis
* tokenization
* stemming
* collocations with Pointwise Mutual Information
* lexical diversity
* corpus analysis
* text summarization

All the documentation for this project can be found in the book and wiki. 

PHP Text Analysis Book & Wiki
=============

A book is in the works and your contributions are needed. You can find the book
at https://github.com/yooper/php-text-analysis-book


Also, documentation for the library resides in the wiki, too. 
https://github.com/yooper/php-text-analysis/wiki


Installation Instructions
=============

Add PHP Text Analysis to your project
```
composer require yooper/php-text-analysis
```

### Tokenization
```php
$tokens = tokenize($text);
```

You can customize which type of tokenizer to tokenize with by passing in the name of the tokenizer class
```php
$tokens = tokenize($text, \TextAnalysis\Tokenizers\PennTreeBankTokenizer::class);
```
The default tokenizer is **\TextAnalysis\Tokenizers\GeneralTokenizer::class** . Some tokenizers require parameters to be set upon instantiation. 

### Normalization
By default, **normalize_tokens** uses the function **strtolower** to lowercase all the tokens. To customize
the normalize function, pass in either a function or a string to be used by array_map. 

```php
$normalizedTokens = normalize_tokens(array $tokens); 
```

```php
$normalizedTokens = normalize_tokens(array $tokens, 'mb_strtolower');

$normalizedTokens = normalize_tokens(array $tokens, function($token){ return mb_strtoupper($token); });
```

### Frequency Distributions

The call to **freq_dist** returns a [FreqDist](https://github.com/yooper/php-text-analysis/blob/master/src/Analysis/FreqDist.php) instance. 
```php
$freqDist = freq_dist(tokenize($text));
```

### Ngram Generation
By default bigrams are generated.
```php
$bigrams = ngrams($tokens);
```
Customize the ngrams
```php
// create trigrams with a pipe delimiter in between each word
$trigrams = ngrams($tokens,3, '|');
```
 
### Stemming
By default stem method uses the Porter Stemmer.
```php
$stemmedTokens = stem($tokens);
```
You can customize which type of stemmer to use by passing in the name of the stemmer class name
```php
$stemmedTokens = stem($tokens, \TextAnalysis\Stemmers\MorphStemmer::class);
```

### Keyword Extract with Rake
There is a short cut method for using the Rake algorithm. You will need to clean
your data prior to using. Second parameter is the ngram size of your keywords to extract.
```php
$rake = rake($tokens, 3);
$results = $rake->getKeywordScores();
```

### Sentiment Analysis with Vader
Need Sentiment Analysis with PHP Use Vader, https://github.com/cjhutto/vaderSentiment .
The PHP implementation can be invoked easily. Just normalize your data before hand.
```php
$sentimentScores = vader($tokens);
```

### Document Classification with Naive Bayes
Need to do some docucment classification with PHP, trying using the Naive Bayes
implementation. An example of classifying movie reviews can be found in the unit
tests

```php
$nb = naive_bayes();
$nb->train('mexican', tokenize('taco nacho enchilada burrito'));        
$nb->train('american', tokenize('hamburger burger fries pop'));  
$nb->predict(tokenize('my favorite food is a burrito'));
```



