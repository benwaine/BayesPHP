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

/**
 * BayesPHP\WordCounter counts words in text samples. Used by BayesPHP\Sample to
 * calculate the word based probabilities.
 *
 * @package    BayesPHP
 * @subpackage WordCounter
 * @author     Ben Waine
 */
class WordCounter
{
    /**
     * An array containing the word counts for the samples of text added using
     * the addSampleMethod().
     * 
     * @var array 
     */
    private $counts;

    /**
     * Initialises an instance of the BayesPHP\WordCounter.
     */
    public function __construct()
    {
        $this->counts = array();
    }

    /**
     * Adds a sample of text to the word counts recorded by this class.
     *
     * @param string $string Text sample
     *
     * @return void
     */
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

    /**
     * Gets an array containing the counts of all the strings submitted using the
     * addToSample() method.
     *
     * @return array
     */
    public function getWordCounts()
    {
        return $this->counts;
    }

    /**
     * Reset the word counter. This makes it suitable for reuse.
     *
     * @return void
     */
    public function reset()
    {
        $this->counts = array();
    }
}


