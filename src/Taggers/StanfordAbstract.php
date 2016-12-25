<?php


namespace TextAnalysis\Taggers;

use RuntimeException;
use TextAnalysis\Filters\PunctuationFilter;
use TextAnalysis\Tokenizers\WhitespaceTokenizer;

/**
 * Abstract class for the Stanford NerTagger and PosTagger to extend
 * @author yooper
 */
abstract class StanfordAbstract 
{
    /**
     *
     * @var array options passed the java vm
     */
    protected $javaOptions = [];
    
    /**
     *
     * @var string Path to the classifier you want to use 
     */
    protected $classifierPath = null;
    
    /**
     * Path to the stanford NER jar
     * @var string
     */
    protected $jarPath = null;
   
    /**
     * Place for storing the tokenized words to 
     * @var string
     */
    protected $tmpFilePath = null;
        
    /**
     * The separators between tokens
     * @var string
     */
    protected $separator = null;
    
    /**
     *
     * @var string Output from proc_open
     */
    protected $output;
    
    /**
     *
     * @var string Errors from proc_open
     */
    protected $errors;
    
    
    /**
     * 
     * @param string $jarPath
     * @param string $classifierPath
     * @param string $javaOptions
     */
    public function __construct($jarPath, $classifierPath, $javaOptions = ['-mx700m'], $separator = '/') 
    {
        $this->jarPath = $jarPath;
        $this->classifierPath = $classifierPath;
        $this->javaOptions = $javaOptions;
        $this->separator = $separator;
    }
    
    /**
     * 
     * @return string
     */
    public function getTmpFilePath()
    {
        return $this->tmpFilePath;
    }
    
    /**
     * @throws \RuntimeException
     * @param array $tokens Use a tokenizer
     */
    public function tag(array $tokens)
    {  
        $this->verify();           
        //write tokens to temp file
        file_put_contents($this->getTmpFilePath(), implode($this->getSeparator(), $tokens));                        
        $this->exec();
               
        return $this->getParsedOutput();        
    }

    /**
     * Requires that class output var be populated. Punctuation may cause issues
     * @return array
     */
    abstract protected function getParsedOutput();
    
    
    /**
     * Separator used between tokens
     * @return string default is /
     */
    public function getSeparator()
    {
        return $this->separator;
    }
    
    /**
     * verifies required files exist
     * @throws \RuntimeException
     */
    protected function verify()
    {
        if(!is_file($this->getJarPath())) { 
            throw new RuntimeException("Jar not found {$this->getJarPath()}");
        }
        
        if(!is_file($this->getClassifierPath())) { 
            throw new RuntimeException("Classifier not found {$this->getClassifierPath()}");
        }        
    }
    
    /**
     * Returns the path to the classifier
     * @return string
     */
    public function getClassifierPath()
    {
        return $this->classifierPath;
    }
    
    /**
     * Returns the path to the ner jar
     * @return string
     */
    public function getJarPath()
    {
        return $this->jarPath;
    }
    
    /**
     * 
     * @return array
     */
    public function getJavaOptions()
    {
        return $this->javaOptions;
    }
    
    /**
     * 
     * @return string
     */
    public function getPathToJava()
    {
        if(getenv('JAVA_HOME')) {
            return getenv('JAVA_HOME');
        } else {
            throw new RuntimeException('env JAVA_HOME must be set.');
        }
    }
    
    /**
     * @return string Returns the cli that is passed to proc_open
     */
    abstract public function getCommand();
    
    /**
     * @return string Return based on the OS used
     */
    protected function getPathSeparator()
    {
        if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            return ';';
        } else {
            return ':';
        }
    }
    
    /**
     * Calls the stanford jar file
     * @throws RuntimeException
     */
    protected function exec()
    {
        $descriptors = [
           0 => ["pipe", "r"],  // stdin is a pipe that the child will read from
           1 => ["pipe", "w"],  // stdout is a pipe that the child will write to
           2 => ["pipe", "w"] // stderr is a file to write to
        ];
        
        $process = proc_open($this->getCommand(), $descriptors, $pipes, dirname($this->getJarPath()), []);

        if (is_resource($process)) {
            fclose($pipes[0]); // close stdin pipe
            $this->output = stream_get_contents($pipes[1]);
            $this->errors = stream_get_contents($pipes[2]);
            fclose($pipes[2]);
            fclose($pipes[1]);                       
            if(proc_close($process) === -1) {
                throw new RuntimeException($this->errors);
            }
        }            
    }

    public function __destruct() 
    {
        unset($this->classifierPath);
        unset($this->errors);
        unset($this->jarPath);
        unset($this->javaOptions);
        unset($this->output);
        unset($this->separator);
        if(file_exists($this->tmpFilePath)) {
            unlink($this->tmpFilePath);
        }
        unset($this->tmpFilePath);
    }
}
