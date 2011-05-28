<?php
namespace BayesPHP;
use BayesPHP\Sample\Result as Result;
use BayesPHP\Stemer as Stemer;
use BayesPHP\WordCounter as WordCounter;

class Sample
{

    /**
     * Array of text samples.
     *
     * @var array
     */
    private $sample;

    /**
     * Stemer
     *
     * @var Stemer
     */
    private $stemer;

    /**
     * Word Counter
     *
     * @var WordCounter
     */
    private $counter;

    public function __construct($sample, Stemer $stemer, WordCounter $counter)
    {
        $this->setSample($sample);
        $this->stemer = $stemer;
        $this->counter = $counter;
    }

    public function setSample(array $sample)
    {

        if(!\array_key_exists('p', $sample) || !\array_key_exists('n', $sample))
        {
            throw new \BayesPHP\Exception\BadArgument('Sample must contain both P and N keys');
        }

        if(\count($sample['p']) != \count($sample['n']))
        {
            throw new \BayesPHP\Exception\BadArgument('Positive and Negative samples mus be equal.');
        }

        $this->sample = $sample;
    }

    public function process()
    {
        // Both samples are the same size.
        // as asserted in the setSample method
        $sampleSize = count($this->sample['p']);
        //var_dump($this->sample);
        $positiveWCs = $this->wordCountSample($this->sample['p']);
        $negativeWCs = $this->wordCountSample($this->sample['n']);

        
        $probsPos = $this->calculateProbabilities($positiveWCs, $sampleSize);
        $probsNeg = $this->calculateProbabilities($negativeWCs, $sampleSize);

        $results = $this->handleResults($probsPos, $probsNeg);

        $result = new Result($results);

        return $result;
    }

    private function wordCountSample(array $sample)
    {

        foreach($sample as $string)
        {
            $stemedString = $this->stemer->process($string);

            $this->counter->addToSample($stemedString);
        }

        $counts = $this->counter->getWordCounts();

        $this->counter->reset();

        return $counts;
    }

    private function calculateProbabilities(array $words, $sampleSize)
    {
        $resultArray = array();

        foreach($words as $word => $appearences)
        {
            $resultArray[$word] = $appearences / $sampleSize;
        }

        return $resultArray;
    }

    private function handleResults(array $positive, array $negative)
    {
        $outResults = array();

        foreach($positive as $word => $occur)
        {
            if(array_key_exists($word, $outResults))
            {
                $outResults[$word]['p'] = $occur;
            }
            else
            {
                $outResults[$word] = array('p' => $occur, 'n' => 0);
            }
        }

        unset($word, $occur);

        foreach($negative as $word => $occur)
        {
            if(array_key_exists($word, $outResults))
            {
                $outResults[$word]['n'] = $occur;
            }
            else
            {
                $outResults[$word] = array('p' => 0, 'n' => $occur);
            }
        }

        ksort($outResults);

        return $outResults;
    }



}

