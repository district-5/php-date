<?php
/**
 * District5 Date Library
 *
 * @author      District5 <hello@district5.co.uk>
 * @copyright   District5 <hello@district5.co.uk>
 * @link        https://www.district5.co.uk
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace District5\Utility;

use District5\Utility\Date as D5Date;

/**
 * Stopwatch
 *
 * A pseudo stopwatch object for working with second resolution timing
 */
class Stopwatch
{

    protected $_maxTimeSeconds = null;
    protected $_startTime = -1;
    protected $_isRunning = false;
    protected $_pausedCumulative = 0;

    /**
     * Creates a new instance of Stopwatch
     *
     * @param int|null $maxTimeSeconds
     */
    public function __construct(int $maxTimeSeconds = null)
    {
        $this->reset($maxTimeSeconds);
    }

    public function isRunning()
    {
        return $this->_isRunning;
    }

    public function start()
    {
        if ($this->_isRunning === true)
        {
            return;
        }

        $this->_startTime = D5Date::GetSecondsSinceEpoch();
        $this->_isRunning = true;
    }

    public function pause()
    {
        if ($this->_isRunning === false)
        {
            return;
        }

        $now = D5Date::GetSecondsSinceEpoch();
        $timePassedSeconds = $now - $this->_startTime;
        $this->_pausedCumulative += $timePassedSeconds;

        $this->_startTime = -1;
        $this->_isRunning = false;
    }

    public function reset(int $maxTimeSeconds = null)
    {
        $this->_startTime = -1;
        $this->_isRunning = false;
        $this->_maxTimeSeconds = $maxTimeSeconds;
        $this->_pausedCumulative = 0;
    }

    public function secondsPassed()
    {
        $now = D5Date::GetSecondsSinceEpoch();
        $timePassedSeconds = $now - $this->_startTime;

        return $this->_pausedCumulative + $timePassedSeconds;
    }

    /**
     * Checks if this stopwatch has passed its max time
     *
     * NOTE: if a max time was not set when creating or last resetting the stopwatch, this will always return false
     *
     * @return bool True if the max time has passed, false otherwise
     */
    public function hasMaxTimePassed() : bool
    {
        if ($this->_maxTimeSeconds === null)
        {
            return false;
        }

        return $this->secondsPassed() > $this->_maxTimeSeconds;
    }
}
