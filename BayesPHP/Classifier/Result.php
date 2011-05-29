<?php
namespace BayesPHP\Classifier;

class Result
{

    private $string;

    private $positive;

    private $negative;

    public function __construct($string, $positive, $negative)
    {
        $this->string = $string;
        $this->positive = $positive;
        $this->negative = $negative;
    }

    public function getProbabilities()
    {
        return array('p' => $this->positive, 'n' => $this->negative);
    }

    
}

