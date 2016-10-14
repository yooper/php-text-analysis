<?php

namespace TextAnalysis\Models\Wordnet;

/**
 * A data model captured from the data.* files
 *
 * @author yooper
 */
class Synset 
{
    use \TextAnalysis\Traits\WordnetPointerSymbolMap;
    
    /**
     *
     * @var int Current byte offset in the file represented as an 8 digit decimal integer.
     */
    protected $synsetOffset;
    /**
     *
     * @var string Two digit decimal integer corresponding to the lexicographer file name containing the synset. 
     */
    protected $lexFilenum;

    /**
     *
     * @var string Two digit hexadecimal integer indicating the number of words in the synset.
     */
    protected $wCnt = 0;
    
    /**
     *
     * @var string[]  ASCII form of a word as entered in the synset by the 
     * lexicographer, with spaces replaced by underscore characters (_ ). 
     * The text of the word is case sensitive, in contrast to its form in the 
     * corresponding index. pos file, that contains only lower-case forms. In 
     * data.adj , a word is followed by a syntactic marker if one was 
     * specified in the lexicographer file. A syntactic marker is appended, in 
     * parentheses, onto word without any intervening spaces. 
     */
    protected $words = [];
        
    /**
     *
     * @var int[] One digit hexadecimal integer that, when appended onto lemma , 
     * uniquely identifies a sense within a lexicographer file. lex_id numbers 
     * usually start with 0 , and are incremented as additional senses of the 
     * word are added to the same file, although there is no requirement that 
     * the numbers be consecutive or begin with 0 . Note that a value of 0 is 
     * the default, and therefore is not present in lexicographer files. 
     */
    protected $lexIds = [];    
    
    
    /**
     *
     * @var float Three digit decimal integer indicating the number of pointers from this synset to other synsets. If p_cnt is 000 the synset has no pointers.
     */
    protected $pCnt;
    /**
     *
     * @var Synset[] A pointer from this synset to another. ptr is of the form:
     * pointerSymbol  synsetOffset  pos  source/target. where synsetOffset 
     * is the byte offset of the target synset in the data file corresponding to pos .
     */
    protected $linkedSynsets = [];
    
    /**
    *
    * @var array In data.verb only, a list of numbers corresponding to the 
    * generic verb sentence frames for word s in the synset. frames is of the form:
    * f_cnt   +   f_num  w_num  [ +   f_num  w_num...] 
    * where f_cnt a two digit decimal integer indicating the number of generic 
    * frames listed, f_num is a two digit decimal integer frame number, and 
     * w_num is a two digit hexadecimal integer indicating the word in the 
     * synset that the frame applies to. As with pointers, if this number is 00 
     * , f_num applies to all word s in the synset. If non-zero, it is applicable 
     * only to the word indicated. Word numbers are assigned as described for 
     * pointers. Each f_num  w_num pair is preceded by a +
    */
    protected $frames = [];
    /**
     *
     * @var string Each synset contains a gloss. A gloss is represented as a 
     * vertical bar (| ), followed by a text string that continues until the end 
     * of the line. The gloss may contain a definition, one or more example sentences, or both.
     */
    protected $definition; 
    
    
    /**
     *
     * @var int
     */
    protected $srcWordIdx;
    
    /**
     *
     * @var int
     */
    protected $targetWordIdx;
    
    public function __construct($synsetOffset, $pos) 
    {
        $this->synsetOffset = $synsetOffset;
        $this->pos = $pos;
    }
    
    /**
     * 
     * @param string $word
     * @param int $lexId
     */
    public function addWord($word, $lexId)
    {
        $this->words[] = $word;
        $this->lexIds[] = $lexId;
    }
    
    /**
     * 
     * @param int $wordIdx
     */
    public function setSrcWordIdx($wordIdx)
    {
        $this->srcWordIdx = $wordIdx;
    }

    /**
     * 
     * @return int
     */
    public function getSrcWordIdx()
    {
        return $this->srcWordIdx;
    }
    /**
     * 
     * @param int $wordIdx
     */    
    public function setTargetWordIdx($wordIdx)
    {
        $this->targetWordIdx = $wordIdx;
    }
    
    /**
     * 
     * @return int
     */
    public function getTargetWordIdx()
    {
        return $this->targetWordIdx;
    }
    
    /**
     * 
     * @param \TextAnalysis\Models\Wordnet\Synset $synset
     * @return Synset;
     */
    public function addLinkedSynset(Synset &$synset)
    {
        $this->linkedSynsets[] = $synset;
        return $this;
    }
    
    /**
     * 
     * @return Synset[] Returned synsets are not fully hydrated
     */
    public function getLinkedSynsets()
    {
        return $this->linkedSynsets;
    }
    
    /**
     * @return string[]
     */
    public function getWords()
    {
        return $this->words;
    }
    
    /**
     * 
     * @param string $definition
     */
    public function setDefinition($definition)
    {
        $this->definition = $definition;
    }
    
    /**
     * @return string
     */
    public function getDefinition()
    {
        return $this->definition;
    }
    
}
