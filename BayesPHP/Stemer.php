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
 * BayesPHP\Stemer class stems words by optionally lower casing, removing
 * punctuation and removing any words on a blacklist (common word list).
 *
 * @package    BayesPHP
 * @subpackage Stemer
 * @author     Ben Waine
 */
class Stemer
{
    /**
     * Flag indicating if lower casing will take place.
     *
     * @var boolean
     */
    private $lowerCasing;

    /**
     * An array of punctation or symbols to remove from text.
     * Can be multi charachter eg. :-)
     *
     * @var array
     */
    private $punctuation;

    /**
     * Array of words to remove from the subject.
     * Suggested use: Common words / Terms in original search.
     *
     * @var array
     */
    private $wordBlacklist;

    /**
     * Initiases an instance of the BayesPHP\Stemer class
     *
     * @param boolean $lowerCasing Indicates if lower casing should be applied
     * @param array   $punctuation An array of punctuation or symbols to remove from subject
     */
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

    /**
     * Sets lower casing on / off;
     *
     * @param boolean $lowerCasing Flag
     *
     * @return void
     */
    public function setLowerCasing($lowerCasing)
    {
        $this->lowerCasing = $lowerCasing;
    }

    /**
     * Set an array of punctuation to exclude from the subject.
     *
     * @param array $punctuation Array containing symbols / puntuation
     *
     * @return void
     */
    public function setPunctuation(array $punctuation)
    {
        $this->punctuation = $punctuation;
    }

    /**
     * Set an array of words to use as a blacklist
     *
     * @param array $words Array of words to use as a blacklist
     *
     * @return void
     */
    public function setWordBlacklist(array $words)
    {
        $this->wordBlacklist = $words;
    }

    /**
     * Process the string. Uses blacklist / lower casing / punctuation filters as applied.
     *
     * @param string $string String to stem.
     *
     * @return string
     */
    public function process($string)
    {
        $string = $this->tokenActions($string);

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

    /**
     * Executes any actions required on individual tokens in the string.
     *
     * @param string $string String to carry token actions on.
     *
     * @return string
     */
    private function tokenActions($string)
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

                if(isset($this->wordBlacklist))
                {
                    if($this->checkBlacklist($value))
                    {
                        unset($pieces[$key]);
                    }
                }
            }
        }

        return implode(' ', $pieces);
    }

    /**
     * Checks the blacklist for presence of a word.
     *
     * @param string $word Word to check blacklist for.
     *
     * @return boolean
     */
    private function checkBlacklist($word)
    {
        return (in_array($word, $this->wordBlacklist));
    }

    /**
     * Lower case the string.
     *
     * @param string $string String to lower case.
     *
     * @return string
     */
    private function lowerCase($string)
    {
        return \strtolower($string);
    }

    /**
     * Remove an punctuation or symbols specified in the punctuation array.
     *
     * @param string $string String to filter.
     *
     * @return string
     */
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
