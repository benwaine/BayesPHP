<?php
namespace BayesPHP;

class WordCounter
{
    private $counts;

    public function __construct()
    {
        $this->counts = array();
    }

    public function addToSample($string)
    {
        $words = explode(' ', $string);

        $wordsSampled = array();

        foreach ($words as $word)
        {
            if(!in_array($word, $wordsSampled))
            {
                $wordsSampled[] = $word;

                if(!\array_key_exists($word, $this->counts))
                {
                    $this->counts[$word] = 1;
                }
                else
                {
                    $this->counts[$word]++;
                }
            }
        }
    }

    public function getWordCounts()
    {
        return $this->counts;
    }

    public function reset()
    {
        $this->counts = array();
    }
}


