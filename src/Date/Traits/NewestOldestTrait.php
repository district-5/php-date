<?php
namespace District5\Date\Traits;

use DateTime;

/**
 * Trait NewestOldestTrait
 * @package District5\Date\Traits
 */
trait NewestOldestTrait
{
    /**
     * Get the oldest date.
     *
     * @param DateTime $otherDateTime
     * @return DateTime
     */
    protected function getOldestDate(DateTime $otherDateTime): DateTime
    {
        $otherDateTime = clone $otherDateTime;
        if ($otherDateTime < $this->dateTime) {
            return $otherDateTime;
        }
        return $this->dateTime;
    }

    /**
     * Get the newest date.
     *
     * @param DateTime $otherDateTime
     * @return DateTime
     */
    protected function getNewestDate(DateTime $otherDateTime): DateTime
    {
        $otherDateTime = clone $otherDateTime;
        if ($otherDateTime > $this->dateTime) {
            return $otherDateTime;
        }
        return $this->dateTime;
    }
}
