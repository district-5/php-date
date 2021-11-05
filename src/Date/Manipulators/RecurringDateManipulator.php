<?php

namespace District5\Date\Manipulators;

use DateTime;
use Exception;
use District5\Date\Calculators\Diff;
use District5\Date\Formatters\OutputFormatter;
use District5\Date\Date;

/**
 * Class RecurringDateManipulator
 * @package District5\Date\Manipulators
 */
class RecurringDateManipulator
{
    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * RecurringDateManipulator constructor.
     * @param DateTime $dateTime
     */
    public function __construct(DateTime $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    /**
     * @return DateTime
     */
    public function thisYear(): DateTime
    {
        return $this->dateTime;
    }

    /**
     * @return DateTime|false
     * @noinspection PhpUnused
     */
    public function tomorrow()
    {
        return Date::modify($this->dateTime)->plus()->days(1);
    }

    /**
     * @return DateTime|false
     * @noinspection PhpUnused
     */
    public function yesterday()
    {
        return Date::modify($this->dateTime)->minus()->days(1);
    }

    /**
     * @return DateTime|false
     * @noinspection PhpUnused
     */
    public function nextMonth()
    {
        return Date::modify($this->dateTime)->plus()->months(1);
    }

    /**
     * @return DateTime|false
     * @noinspection PhpUnused
     */
    public function lastMonth()
    {
        return Date::modify($this->dateTime)->minus()->months(1);
    }

    /**
     * @return DateTime|false
     */
    public function nextYear()
    {
        return Date::modify($this->dateTime)->plus()->years(1);
    }

    /**
     * @return DateTime|false
     */
    public function lastYear()
    {
        return Date::modify($this->dateTime)->minus()->years(1);
    }

    /**
     * @return bool
     * @noinspection PhpUnused
     */
    public function isToday(): bool
    {
        try {
            $dt = Date::now()->default();
            $start = $this->generateNormalisedDate();
            $end = DateTime::createFromFormat(
                'Y-m-d H:i:s',
                sprintf(
                    '%s-%s-%s 23:59:59',
                    $dt->format('Y'),
                    $dt->format('m'),
                    $dt->format('d')
                )
            );
            if ($dt >= $start && $dt <= $end) {
                return true;
            }
        } catch (Exception $e) {
        }
        return false;
    }

    /**
     * Get the previous instance of this recurring date.
     *
     * @return DateTime|false
     * @noinspection PhpUnused
     */
    public function getPrevious()
    {
        try {
            $date = $this->generateNormalisedDate();
            if (Date::validateObject($this->dateTime)->isNewerThan($date)) {
                return $this->nextYear();
            }
            return $this->dateTime;
        } catch (Exception $e) {
        }
        return false;
    }

    /**
     * Get the next instance of this recurring date.
     *
     * @return DateTime|false
     * @noinspection PhpUnused
     */
    public function getNext()
    {
        try {
            $date = $this->generateNormalisedDate();
            if (Date::validateObject($this->dateTime)->isOlderThan($date)) {
                return $this->nextYear();
            }
            return $this->dateTime;
        } catch (Exception $e) {
        }
        return false;
    }

    /**
     * @return OutputFormatter
     */
    public function output(): OutputFormatter
    {
        return new OutputFormatter(
            $this->dateTime
        );
    }

    /**
     * @return Diff
     */
    public function diff(): Diff
    {
        return new Diff(
            $this->dateTime
        );
    }

    /**
     * @return Modify
     */
    public function modify(): Modify
    {
        return new Modify(
            $this->dateTime
        );
    }

    /**
     * @return DateTime|false
     * @throws Exception
     */
    private function generateNormalisedDate()
    {
        $dt = new DateTime();
        return DateTime::createFromFormat(
            'Y-m-d H:i:s',
            sprintf(
                '%s-%s-%s 00:00:00',
                $dt->format('Y'),
                $dt->format('m'),
                $dt->format('d')
            )
        );
    }
}
