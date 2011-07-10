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


// Set up autoloading

require_once __DIR__ . '/../BayesPHP/Autoloader.php';

$autoloader = new BayesPHP\Autoloader('BayesPHP', __dir__ . '/..');

$autoloader->register();


// Create a new positive Sample based on some text input

$posSample = array(
                'I love the fox!',
                'What a great fox, im in love',
                'no one loves a fox like I do',
                'foxes are a great pet you would love one',
                'foxes are great I love them so much',
                'if you love foxes you probably should look at this'
);

// Create a new negative sample based on some text inputs

$negSample = array(
                'Oh I hate foxes, dirty animals!',
                'the fox is a dirty animal',
                'foxes are in my dirty hate list',
                'if there is one animal I hate most, its a fox',
                'ew your a dirty fox',
                'no one can hate a dirty fox in the same way I do'
);

// The sample array used in the BayesPHP\Sample class must be in the following
// format array('p' => array(), 'n'=> array())

$textSample = array('p' => $posSample, 'n' => $negSample);


// The BayesPHP\Sample class uses two utility classes a word counter and a stemer.
// The Word counter counts all the words in the psotive and negative samples.

$wordCounter = new BayesPHP\WordCounter();

// The stemer reduces the number tpkens in the sample that have the same meaning.
// eg Hello, hello, hEllo and Hello! are all reduced to 'hello'
// This improves classifier accuracy.

// First parameter dictates lower casing
// Second parameter dictates punctuation to use when steming
$stemer = new BayesPHP\Stemer(true, array(',', '!', '.', ));

// A blacklist of words can be added to the stemer.
// Any words on the list will be removed from the sample pre classification
// Suggestion: use a common word list and always remove the subject word.
$stemer->setWordBlacklist(array('fox', 'i', 'a', 'if'));

$sample = new BayesPHP\Sample($stemer, $wordCounter);
$sample->setSample($textSample);

// Process the sample and produce a result object.
$result = $sample->process();


// The result object is used as the input to the BayesPHP\Classifier object.
// The classifier classifies text inputs based on the results of the sampling process.

// First parameter is the result object.
// Second parameter is a stemer instance. (preferably one with the same settings used in the sample process)
$classifier = new BayesPHP\Classifier($result, $stemer);

$posResult = $classifier->classify('I love that fox');
$negResult = $classifier->classify('I hate dirty foxes!');
$nuResult = $classifier->classify('Nothing to do with foxes');

// The result of a classification is a BayesPHP\Classifier\Result
// You can get a result (on of the classes result constants) or view the probabilities
// of each classification.

// When using the get result method

echo 'Positive Result: ' . $posResult->getResult();

echo PHP_EOL;

var_dump($posResult->getProbabilities());

echo PHP_EOL;

echo 'Negative Result: ' . $negResult->getResult();

echo PHP_EOL;

var_dump($negResult->getProbabilities());

echo PHP_EOL;

echo 'Neutral Result: ' . $nuResult->getResult();

echo PHP_EOL;

var_dump($nuResult->getProbabilities());

echo PHP_EOL;

var_dump($posResult, $negResult);


?>
