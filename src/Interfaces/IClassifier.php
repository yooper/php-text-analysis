<?php
declare(strict_types = 1);
namespace TextAnalysis\Interfaces;

/**
 * Used by classifier algorithms
 * @author yooper
 */
interface IClassifier 
{
    public function train(string $label, array $tokens);
    
    public function predict(array $tokens);
}
