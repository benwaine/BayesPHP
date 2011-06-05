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
namespace BayesPHP\Classifier;

/**
 * Classification Result Class
 *
 * Used to yield the results of a classification by BayesPHP\Classifier
 *
 * @package    BayesPHP
 * @subpackage Classifier
 * @author     Ben Waine
 */
class Result
{

    /**
     * The sibject of the classification.
     *
     * @var string
     */
    private $string;

    /**
     * The probability the subject is positive.
     *
     * @var double
     */
    private $positive;

    /**
     * The probability the subject is negative.
     *
     * @var double
     */
    private $negative;

    /**
     * The reporting threshold. Probabilties must exceed this threshold in
     * order to be reported as the result of classification.
     *
     * @var double
     */
    private $threshold = 0.7;

    /**
     * Used by the getProbabilities() method to indicate the return of the positive probability.
     * Denotes the index in a probability array that means 'positive'.
     *
     * @var string
     */
    const RESULT_POSITIVE = 'p';

    /**
     * Used by the getProbabilities() method to indicate the return of the negative probability.
     * Denotes the index in a probability array that means 'negative'.
     *
     * @var string
     */
    const RESULT_NEGATIVE = 'n';

    /**
     * Used by the getProbabilities() method to indicate the return of the both probabilitys.
     *
     * @var integer
     */
    const RESULT_BOTH = 3;

    /**
     * Used by the getResult method to inidcate subject is classified positive.
     *
     * @var integer
     */
    const RESULT_TYPE_POS = 1;

    /**
     * Used by the getResult method to indicate subject is classified negative.
     *
     * @var integer
     */
    const RESULT_TYPE_NEG = 2;

    /**
     * Used by the getResult method to indicate subject cannot be classified.
     *
     * @var intger
     */
    const RESULT_TYPE_INCONCLUSIVE = 3;

    /**
     * Initialises an instance of the BayesPHP\Sample\Result class.
     *
     * @param string $string    The classified string
     * @param double $positive  Probability that $string is positive
     * @param double $negative  Probability that $string in negative
     * @param double $threshold Value to use as the threshold when reporting results
     */
    public function __construct($string, $positive, $negative, $threshold = 0.7)
    {
        $this->string = $string;
        $this->positive = $positive;
        $this->negative = $negative;
        $this->threshold = $threshold;
    }

    /**
     * Returns the probabilities that the classified string resides in a class.
     *
     * @param integer $resultType Used to specify which classification class to return. Default to 'BOTH'.
     *
     * @return array|double
     */
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

    /**
     * Returns the result of the classification process.
     * The 'threshold' value is taken into account. (See constructor)
     * 
     * @return integer
     */
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

