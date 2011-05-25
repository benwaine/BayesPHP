<?php
namespace BayesPHP;

class Stemer
{
    private $lowerCasing;

    private $punctuation;

    public function __construct($lowerCasing = null, $punctuation = null)
    {
        if(isset($lowerCasing))
        {
            $this->setLowerCasing($lowerCasing);
        }

        if(isset($punctuation))
        {
            $this->setPunctuation($punctuation);
        }

    }

    public function setLowerCasing($lowerCasing)
    {
        $this->lowerCasing = $lowerCasing;
    }

    public function setPunctuation(array $punctuation)
    {
        $this->punctuation = $punctuation;
    }
    
    public function process($string)
    {
        $string = $this->trim($string);

        if($this->lowerCasing)
        {
            $string = $this->lowerCase($string);
        }

        if(isset($this->punctuation))
        {
            $string = $this->punctuation($string);
        }

        return $string;
    }

    private function trim($string)
    {
        $string = trim($string);

        $pieces = explode(' ', $string);

        foreach($pieces as $key => &$value)
        {
            if($value == '')
            {
                unset($pieces[$key]);
            }
            else
            {
                trim($value);
            }
        }

        return implode(' ', $pieces);
    }

    private function lowerCase($string)
    {
        return \strtolower($string);
    }

    private function punctuation($string)
    {

        // Sort punctuation array into character length.
        // This makes the punctuation filter compatiable with smiles :) or :-) etc

        usort($this->punctuation, function($a, $b){
        
            if(\strlen($a) > \strlen($b))
            {
                return -1;
            }
            elseif(\strlen($a) < \strlen($b))
            {
                return 1;
            }
            else
            {
                return 0;
            }

        });        

        if(isset($this->punctuation) && is_array($this->punctuation))
        {
            foreach($this->punctuation as $p)
            {
                $string = \str_replace($p, '', $string);
            }
        }

        return $string;
    }

}

?>
