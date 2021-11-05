<?php

namespace District5\Date\Calculators;

use DateTime;
use District5\Date\Abstracts\AbstractConstructor;
use District5\Date\Date;
use District5\Date\Traits\NewestOldestTrait;

/**
 * Class Diff
 * @package District5\Date\Calculators
 */
class Diff extends AbstractConstructor
{
    use NewestOldestTrait;

    /**
     * Get the number of whole millennia difference between the original date and this $otherDateTime
     *
     * @param DateTime|null $otherDateTime
     * @return int
     */
    public function millennia(?DateTime $otherDateTime = null): int
    {
        $years = $this->dateTime->diff(
            $this->getDateTimeToUse($otherDateTime)
        )->y;
        if ($years < 1000) {
            return 0;
        }

        return intval(floor($years / 1000));
    }

    /**
     * Get the number of whole centuries difference between the original date and this $otherDateTime
     *
     * @param DateTime|null $otherDateTime
     * @return int
     */
    public function centuries(?DateTime $otherDateTime = null): int
    {
        $years = $this->dateTime->diff(
            $this->getDateTimeToUse($otherDateTime)
        )->y;
        if ($years < 100) {
            return 0;
        }

        return intval(floor($years / 100));
    }

    /**
     * Get the number of whole decades difference between the original date and this $otherDateTime
     *
     * @param DateTime|null $otherDateTime
     * @return int
     */
    public function decades(?DateTime $otherDateTime = null): int
    {
        $years = $this->dateTime->diff(
            $this->getDateTimeToUse($otherDateTime)
        )->y;
        if ($years < 10) {
            return 0;
        }

        return intval(floor($years / 10));
    }

    /**
     * Get the number of whole years difference between the original date and this $otherDateTime
     *
     * @param DateTime|null $otherDateTime
     * @return int
     */
    public function years(?DateTime $otherDateTime = null): int
    {
        $toUse = $this->getDateTimeToUse($otherDateTime);

        return $this->dateTime->diff($toUse)->y;
    }

    /**
     * Get the number of whole months difference between the original date and this $otherDateTime
     *
     * @param DateTime|null $otherDateTime
     * @return int
     */
    public function months(?DateTime $otherDateTime = null): int
    {
        $diff = $this->dateTime->diff(
            $this->getDateTimeToUse($otherDateTime)
        );

        return $diff->m + ($diff->y * 12);
    }

    /**
     * Get the number of whole weeks difference between the original date and this $otherDateTime
     *
     * @param DateTime|null $otherDateTime
     * @return int
     */
    public function weeks(?DateTime $otherDateTime = null): int
    {
        $days = $this->days($otherDateTime);
        if ($days > 0) {
            return intval(floor($days / 7));
        }
        return 0;
    }

    /**
     * Get the number of whole days difference between the original date and this $otherDateTime
     *
     * @param DateTime|null $otherDateTime
     * @return int
     */
    public function days(?DateTime $otherDateTime = null): int
    {
        $toUse = $this->getDateTimeToUse($otherDateTime);
        $diff = date_diff(
            $this->getOldestDate($toUse),
            $this->getNewestDate($toUse)
        )->format('%a');
        return intval($diff);
    }

    /**
     * Get the number of whole hours difference between the original date and this $otherDateTime
     *
     * @param DateTime|null $otherDateTime
     * @return int
     */
    public function hours(?DateTime $otherDateTime = null): int
    {
        if (0 === $diff = $this->getSecondsBetweenDates($this->getDateTimeToUse($otherDateTime))) {
            return 0;
        }
        $diff = (($diff / 60) / 60);
        return round($diff);
    }

    /**
     * Get the number of whole minutes difference between the original date and this $otherDateTime
     *
     * @param DateTime|null $otherDateTime
     * @return int
     */
    public function minutes(?DateTime $otherDateTime = null): int
    {
        if (0 === $diff = $this->getSecondsBetweenDates($this->getDateTimeToUse($otherDateTime))) {
            return 0;
        }
        return round(($diff / 60));
    }

    /**
     * Get the number of whole seconds difference between the original date and this $otherDateTime
     *
     * @param DateTime|null $otherDateTime
     * @return int
     */
    public function seconds(?DateTime $otherDateTime = null): int
    {
        return $this->getSecondsBetweenDates(
            $this->getDateTimeToUse($otherDateTime)
        );
    }

    /**
     * Get the number of seconds between 2 DateTimes.
     *
     * @param DateTime $otherDateTime
     * @return int
     */
    protected function getSecondsBetweenDates(DateTime $otherDateTime): int
    {
        $newestTs = $this->getNewestDate($otherDateTime)->getTimestamp();
        $oldestTs = $this->getOldestDate($otherDateTime)->getTimestamp();

        return $newestTs - $oldestTs;
    }

    /**
     * Handles the incoming DateTime potentially being null and initialises a new()->default() instance.
     *
     * @param DateTime|null $otherDateTime
     * @return DateTime
     */
    protected function getDateTimeToUse(?DateTime $otherDateTime = null): DateTime
    {
        if (null === $otherDateTime) {
            return Date::now()->default();
        }
        return clone $otherDateTime;
    }
}
