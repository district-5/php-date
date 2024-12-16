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
    public static function numberDaysInMonthByGivenValues(?int $month = null, ?int $year = null): int
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
    public static function numberDaysLeftInMonthByGivenValues(?int $day = null, ?int $month = null, ?int $year = null): int
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
        return $this->getNewestDate(
                $otherDateTime
            )->getTimestamp() - $this->getOldestDate(
                $otherDateTime
            )->getTimestamp();
    }

    /**
     * Is the given DateTime older than $provided
     * (Alias for DateTimeValidator::isOlderThan())
     *
     * @param DateTime $provided
     * @return bool
     * @see \District5\Date\Validators\DateTimeValidator::isOlderThan()
     */
    public function isOlderThan(DateTime $provided): bool
    {
        return Date::validateObject($this->dateTime)->isOlderThan($provided);
    }

    /**
     * Is the given DateTime newer than $provided
     * (Alias for DateTimeValidator::isNewerThan())
     *
     * @param DateTime $provided
     * @return bool
     * @see \District5\Date\Validators\DateTimeValidator::isNewerThan()
     */
    public function isNewerThan(DateTime $provided): bool
    {
        return Date::validateObject($this->dateTime)->isNewerThan($provided);
    }

    /**
     * Is the given DateTime older than or equal to $provided DateTime
     * (Alias for DateTimeValidator::isOlderThanOrEqualTo())
     *
     * @param DateTime $provided
     * @return bool
     * @noinspection PhpUnused
     * @see          \District5\Date\Validators\DateTimeValidator::isOlderThanOrEqualTo()
     */
    public function isOlderThanOrEqualTo(DateTime $provided): bool
    {
        return Date::validateObject($this->dateTime)->isOlderThanOrEqualTo($provided);
    }

    /**
     * Is the given DateTime newer than or equal to $provided DateTime
     * (Alias for DateTimeValidator::isNewerThanOrEqualTo())
     *
     * @param DateTime $provided
     * @return bool
     * @noinspection PhpUnused
     * @see          \District5\Date\Validators\DateTimeValidator::isNewerThanOrEqualTo()
     */
    public function isNewerThanOrEqualTo(DateTime $provided): bool
    {
        return Date::validateObject($this->dateTime)->isNewerThanOrEqualTo($provided);
    }
}
