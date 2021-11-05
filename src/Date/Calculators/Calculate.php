<?php
namespace District5\Date\Calculators;

use DateTime;
use District5\Date\Abstracts\AbstractConstructor;
use District5\Date\Date;
use District5\Date\Traits\NewestOldestTrait;

/**
 * Class Calculate
 * @package District5\Date\Calculators
 */
class Calculate extends AbstractConstructor
{
    use NewestOldestTrait;

    /**
     * @return int
     */
    public function numberDaysInMonth(): int
    {
        return self::numberDaysInMonthByGivenValues(
            intval($this->dateTime->format('n')),
            intval($this->dateTime->format('Y'))
        );
    }

    /**
     * @param int|null $month
     * @param int|null $year
     * @return int
     */
    public static function numberDaysInMonthByGivenValues(int $month=null, int $year=null): int
    {
        $dt = Date::now()->default();
        if ($month === null) {
            $month = intval(($dt)->format('n'));
        }
        if ($year === null) {
            $year = intval(($dt)->format('Y'));
        }
        return cal_days_in_month(CAL_GREGORIAN, $month, $year);
    }

    /**
     * @return int
     */
    public function numberDaysLeftInMonth(): int
    {
        return self::numberDaysLeftInMonthByGivenValues(
            intval($this->dateTime->format('j')),
            intval($this->dateTime->format('n')),
            intval($this->dateTime->format('Y'))
        );
    }

    /**
     * @param int|null $day
     * @param int|null $month
     * @param int|null $year
     * @return int
     */
    public static function numberDaysLeftInMonthByGivenValues(int $day=null, int $month=null, int $year=null): int
    {
        $dt = Date::now()->default();
        if ($day === null) {
            $day = intval(($dt)->format('n'));
        }
        if ($month === null) {
            $month = intval(($dt)->format('n'));
        }
        if ($year === null) {
            $year = intval(($dt)->format('Y'));
        }
        $total = self::numberDaysInMonthByGivenValues(
            $month,
            $year
        );
        if ($day > $total) {
            return false;
        }
        return $total - $day;
    }

    /**
     * @param DateTime $otherDateTime
     * @return int
     * @noinspection PhpUnused
     */
    public function hours(DateTime $otherDateTime): int
    {
        $otherDateTime = clone $otherDateTime;
        $diff = $this->getNewestDate(
            $otherDateTime
        )->getTimestamp() - $this->getOldestDate(
            $otherDateTime
        )->getTimestamp();
        if ($diff === 0) {
            return 0;
        }
        $diff = (($diff / 60) / 60);
        return round($diff);
    }

    /**
     * @param DateTime $otherDateTime
     * @return int
     * @noinspection PhpUnused
     */
    public function minutes(DateTime $otherDateTime): int
    {
        $otherDateTime = clone $otherDateTime;
        $diff = $this->getNewestDate(
            $otherDateTime
        )->getTimestamp() - $this->getOldestDate(
            $otherDateTime
        )->getTimestamp();
        if ($diff === 0) {
            return 0;
        }
        return round(($diff / 60));
    }

    /**
     * @param DateTime $otherDateTime
     * @return int
     * @noinspection PhpUnused
     */
    public function seconds(DateTime $otherDateTime): int
    {
        $otherDateTime = clone $otherDateTime;
        $diff = $this->getNewestDate(
            $otherDateTime
        )->getTimestamp() - $this->getOldestDate(
            $otherDateTime
        )->getTimestamp();
        return intval($diff);
    }

    /**
     * Is the given DateTime older than $provided
     * (Alias for DateTimeValidator::isOlderThan())
     *
     * @see \District5\Date\Validators\DateTimeValidator::isOlderThan()
     * @param DateTime $provided
     * @return bool
     */
    public function isOlderThan(DateTime $provided): bool
    {
        return Date::validateObject($this->dateTime)->isOlderThan($provided);
    }

    /**
     * Is the given DateTime newer than $provided
     * (Alias for DateTimeValidator::isNewerThan())
     *
     * @see \District5\Date\Validators\DateTimeValidator::isNewerThan()
     * @param DateTime $provided
     * @return bool
     */
    public function isNewerThan(DateTime $provided): bool
    {
        return Date::validateObject($this->dateTime)->isNewerThan($provided);
    }

    /**
     * Is the given DateTime older than or equal to $provided DateTime
     * (Alias for DateTimeValidator::isOlderThanOrEqualTo())
     *
     * @see \District5\Date\Validators\DateTimeValidator::isOlderThanOrEqualTo()
     * @param DateTime $provided
     * @return bool
     * @noinspection PhpUnused
     */
    public function isOlderThanOrEqualTo(DateTime $provided): bool
    {
        return Date::validateObject($this->dateTime)->isOlderThanOrEqualTo($provided);
    }

    /**
     * Is the given DateTime newer than or equal to $provided DateTime
     * (Alias for DateTimeValidator::isNewerThanOrEqualTo())
     *
     * @see \District5\Date\Validators\DateTimeValidator::isNewerThanOrEqualTo()
     * @param DateTime $provided
     * @return bool
     * @noinspection PhpUnused
     */
    public function isNewerThanOrEqualTo(DateTime $provided): bool
    {
        return Date::validateObject($this->dateTime)->isNewerThanOrEqualTo($provided);
    }
}
