<?php

/**
 * District5 - Date
 *
 * @copyright District5
 *
 * @author Mark Morgan <mark.morgan@district5.co.uk>
 * @link https://www.district5.co.uk
 *
 * @license This software and associated documentation (the "Software") may not be
 * used, copied, modified, distributed, published or licensed to any 3rd party
 * without the written permission of District5 or its author.
 *
 * The above copyright notice and this permission notice shall be included in
 * all licensed copies of the Software.
 *
 */
namespace District5\Utility;

use District5\Utility\Date as D5Date;

/**
 * Stopwatch
 *
 * A pseudo stopwatch object for working with second resolution timing
 *
 * @author Mark Morgan
 *
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
