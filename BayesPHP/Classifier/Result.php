<?php
namespace BayesPHP\Classifier;

class Result
{

    private $string;

    private $positive;

    private $negative;

    private $threshold = 0.7;

    const RESULT_POSITIVE = 'p';

    const RESULT_NEGATIVE = 'n';

    const RESULT_BOTH = 3;

    const RESULT_TYPE_POS = 1;

    const RESULT_TYPE_NEG = 2;

    const RESULT_TYPE_INCONCLUSIVE = 3;

    public function __construct($string, $positive, $negative, $threshold = 0.7)
    {
        $this->string = $string;
        $this->positive = $positive;
        $this->negative = $negative;
        $this->threshold = 0.7;
    }

    public function getProbabilities($resultType = self::RESULT_BOTH)
    {
        if($resultType == self::RESULT_BOTH)
        {
            return array('p' => $this->positive, 'n' => $this->negative);
        }
        elseif($resultType == self::RESULT_POSITIVE)
        {
           return $this->positive;
        }
        elseif($resultType == self::RESULT_NEGATIVE)
        {
            return $this->negative;
        }
        else
        {
            throw new \BayesPHP\Exception\BadArgument('Invalid Result Type Specified');
        }
    }

    public function getResult()
    {
        if($this->positive == $this->negative)
        {
            return self::RESULT_TYPE_INCONCLUSIVE;
        }
        else
        {
            $res = ($this->positive > $this->negative) ? self::RESULT_TYPE_POS : self::RESULT_TYPE_NEG;

            if($res == self::RESULT_TYPE_POS && ($this->positive > $this->threshold))
            {
                return $res;
            }
            elseif($res == self::RESULT_TYPE_NEG && ($this->negative > $this->threshold))
            {
                return $res;
            }
            else
            {
                return self::RESULT_TYPE_INCONCLUSIVE;
            }
        }
    }

    
}

