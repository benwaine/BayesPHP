<?php
namespace BayesPHP\Sample;

class Result
{
    const RESULT_POS = 'p';

    const RESULT_NEG = 'n';

    const RESULT_BOTH = 3;


    private $probabilities;

    public function __construct(array $result)
    {
        $this->probabilities = $result;
    }

    public function getAllProbabilities()
    {
        return $this->probabilities;
    }

    public function getWordProbability($word, $return = self::RESULT_BOTH)
    {
        if(array_key_exists($word, $this->probabilities))
        {
            $returnProbs = array_slice($this->probabilities[$word], 0, 2);
        }
        else
        {
            $returnProbs = array('p' => 0, 'n' => 0);
        }

        if($return == self::RESULT_BOTH)
        {
            return $returnProbs;
        }
        else
        {
            return $returnProbs[$return];
        }
    }

}

