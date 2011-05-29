<?php

namespace BayesPHP;

use \BayesPHP\Sample\Result as SResult;

class Classifier
{

    /**
     * Sample Result Object
     *
     * @var SResult
     */
    private $resultOb;
    /**
     * Stemer Object
     * 
     * @var Stemer
     */
    private $stemer;

    public function __construct(SResult $result, Stemer $stemer)
    {
        $this->resultOb = $result;

        $this->stemer = $stemer;
    }

    public function classify($string)
    {

        $stemedString = $this->stemer->process($string);

        $words = explode(' ', $string);

        $positiveProbs = array();
        $negativeProbs = array();

        foreach($words as $word)
        {
            $probs = $this->resultOb->getWordProbability($word);

            if($probs['p'] != 0)
            {
                $positiveProbs[] = $probs['p'];
            }
            if($probs['n'] != 0)
            {
                $negativeProbs[] = $probs['n'];
            }
        }

        if(count($positiveProbs) > 0)
        {
            $posProbs = $this->calculateProbability($positiveProbs);
        }
        else
        {
            $posProbs = 0;
        }

        if(count($negativeProbs) > 0)
        {
            $negProbs = $this->calculateProbability($negativeProbs);
        }
        else
        {
            $negProbs = 0;
        }

        return new Classifier\Result($string, $posProbs, $negProbs);
    }

    private function calculateProbability($probs)
    {
        $products = \array_product($probs);

        $oneMinus = array();

        foreach($probs as $value)
        {
            $oneMinus[] = 1 - $value;
        }

        $oneMinusProds = \array_product($oneMinus);

        $returnProbs = $products / ($products + $oneMinusProds);

        return round($returnProbs, 2);
    }

}

