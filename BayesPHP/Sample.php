<?php
/*
* THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
* "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
* LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
* A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
* OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
* SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
* LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
* DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
* THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
* (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
* OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*
* This software consists of work done by Ben Waine
* and is licensed under the LGPL. For more information, see
* http://ben-waine.co.uk/
*/

namespace BayesPHP;
use BayesPHP\Sample\Result as Result;
use BayesPHP\Stemer as Stemer;
use BayesPHP\WordCounter as WordCounter;

/**
 * BayesPHP\Sample analyses an array of samples and produces a result object used
 * by the BayesPHP\Classifer to classify strings of text.
 *
 * @package    BayesPHP
 * @subpackage Sample
 * @author     Ben Waine
 */
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

    /**
     * Initialises an instance of BayesPHP\Sample.
     * 
     * @param array       $sample  An array containing an equal number of positive and negative text samples. With indicies array('p' => array(), 'n' => array()).
     * @param Stemer      $stemer  A stemer used to stem the words in each of the text samples.
     * @param WordCounter $counter A word counter used to count words in text samples.
     */
    public function __construct(Stemer $stemer, WordCounter $counter)
    {
        $this->stemer = $stemer;
        $this->counter = $counter;
    }

    /**
     * Set the sample used to produce the BayesPHP\Sample\Result object.
     * MUST be in the format array('p' => array(), 'n'=> array()).
     * MUST have an equal number of positive and negaitive text samples.
     *
     * @param array $sample Text samples.
     *
     * @return void
     */
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

    /**
     * Process the sample supplied and produce a BayesPHP\Sample\Result object.
     *
     * @return \BayesPHP\Sample\Result
     */
    public function process()
    {
        // Both samples are the same size.
        // as asserted in the setSample method
        $sampleSize = count($this->sample['p']);

        $positiveWCs = $this->wordCountSample($this->sample['p']);
        $negativeWCs = $this->wordCountSample($this->sample['n']);

        
        $probsPos = $this->calculateProbabilities($positiveWCs, $sampleSize);
        $probsNeg = $this->calculateProbabilities($negativeWCs, $sampleSize);

        $results = $this->handleResults($probsPos, $probsNeg);

        $result = new Result($results);

        return $result;
    }

    /**
     * Counts the words in a text sample.
     *
     * @param array $sample An array of text samples.
     *
     * @return array
     */
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

    /**
     * Calculate the probability that a word appeared in set of text samples.
     *
     * @param array   $words An array of word counts.
     * @param integer $sampleSize
     *
     * @return array
     */
    private function calculateProbabilities(array $words, $sampleSize)
    {
        $resultArray = array();

        foreach($words as $word => $appearences)
        {
            $resultArray[$word] = $appearences / $sampleSize;
        }

        return $resultArray;
    }

    /**
     * Takes an array of positive and negative probabilities and
     * reindexes them to produce an array in the format array('word' => array('p' => 0.33, 'n' => 0.12))
     *
     * @param array $positive Positive word probabilites
     * @param array $negative Negative word probabilites
     *
     * @return array
     */
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
        unset ($word, $occur);
        
        ksort($outResults);

        return $outResults;
    }



}

