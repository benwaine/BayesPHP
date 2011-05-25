<?php
namespace BayesPHP;
use \BayesPHP\Exception\BadArgument;

class Sample
{

    private $sample;

    public function setSample(array $sample)
    {

        if(!\array_key_exists('p', $sample) || \array_key_exists('n', $sample))
        {
            throw new \BayesPHP\Exception\BadArgument('Sample must contain both P and N keys');
        }

        $this->sample = $sample;
    }

}

