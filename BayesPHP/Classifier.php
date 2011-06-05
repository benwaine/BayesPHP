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

use \BayesPHP\Sample\Result as SResult;

/**
 * BayesPHP\Classifier uses an instance of BayesPHP\Sample\Result to classify
 * strings as positive or negative based the words composing previous samples.
 *
 * @package    BayesPHP
 * @subpackage Classifer
 * @author     Ben Waine
 */
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

    /**
     * Initialises an instance of BayesPHP\Classifer
     *
     * @param SResult $result The result of a sampling process conducted with BayesPHP\Sample
     * @param Stemer  $stemer A Stemer used to stem incoming samples
     */
    public function __construct(SResult $result, Stemer $stemer)
    {
        $this->resultOb = $result;

        $this->stemer = $stemer;
    }

    /**
     * Classify a string using the results of a sampling process.
     *
     * @param string $string String to classify
     *
     * @return Classifier\Result
     */
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

    /**
     * Combines the word probabilites of a sample using Bayes formular.
     *
     * @param array $probs
     *
     * @return double
     */
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

