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
namespace BayesPHP\Sample;

/**
 * The BayesPHP\Sample\Result object contains the results of the BayesPHP\Sample object.
 * It wraps an array with convience methods used for locating the probabilities attached to
 * words encountered in the initial sampling process.
 *
 * @package    BayesPHP
 * @subpackage Sample
 * @author     Ben Waine
 */
class Result
{
    /**
     * Used by the getWordProbability() method to return only a positive probability.
     * Denotes the array index used by a positive probability.
     *
     * @var string
     */
    const RESULT_POS = 'p';

    /**
     * Used by the getWordProbability() method to return only a negative probability.
     * Denotes the array index used by a negative probability.
     *
     * @var string
     */
    const RESULT_NEG = 'n';

    /**
     * Used by the getWordProbability() method to return both probabilities.
     *
     * @var integer
     */
    const RESULT_BOTH = 3;


    /**
     * An array of probabilites for words encountered in the BayesPHP\Sample process.
     *
     * @var array
     */
    private $probabilities;

    /**
     * Initialises an instance of BayesPHP\Sample\Result class.
     * Provides a wrapper around the results of a sampling process.
     * Used to get the probabilities that a word will appear in a
     * positive or negative context based on previous encounters.
     *
     * @param array $result Probabilites for words encountered by BayesPHP\Sample
     */
    public function __construct(array $result)
    {
        $this->probabilities = $result;
    }

    /**
     * Returns an array consisting of probabilties that words appear in a sample
     * based on number of encounters in previous samples.
     *
     * @return array
     */
    public function getAllProbabilities()
    {
        return $this->probabilities;
    }

    /**
     * Returns an array consisting of probabilties that a given word appears in a sample
     * based on number of encounters in previous samples.
     *
     * @return array|double
     */
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

