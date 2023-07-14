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

namespace District5\Date\Validators;

use DateInterval;
use DateTime;
use Exception;
use District5\Date\Abstracts\AbstractConstructor;
use District5\Date\Date;

/**
 * Class DateTimeValidator
 * @package Date\Validators
 */
class DateTimeValidator extends AbstractConstructor
{
    const MONTH_JANUARY = 1;
    const MONTH_FEBRUARY = 2;
    const MONTH_MARCH = 3;
    const MONTH_APRIL = 4;
    const MONTH_MAY = 5;
    const MONTH_JUNE = 6;
    const MONTH_JULY = 7;
    const MONTH_AUGUST = 8;
    const MONTH_SEPTEMBER = 9;
    const MONTH_OCTOBER = 10;
    const MONTH_NOVEMBER = 11;
    const MONTH_DECEMBER = 12;

    const DAY_MONDAY = 1;
    const DAY_TUESDAY = 2;
    const DAY_WEDNESDAY = 3;
    const DAY_THURSDAY = 4;
    const DAY_FRIDAY = 5;
    const DAY_SATURDAY = 6;
    const DAY_SUNDAY = 7;

    /**
     * Is this DateTime a AM represented instance?
     *
     * @return bool
     */
    public function isAM(): bool
    {
        return $this->dateTime->format('A') === 'AM';
    }

    /**
     * Is this DateTime a PM represented instance?
     *
     * @return bool
     */
    public function isPM(): bool
    {
        return $this->dateTime->format('A') === 'PM';
    }

    /**
     * Is the given DateTime older than $provided
     *
     * @param DateTime $provided
     * @return bool
     */
    public function isOlderThan(DateTime $provided): bool
    {
        return $this->dateTime < $provided;
    }

    /**
     * Is the given DateTime newer than $provided
     *
     * @param DateTime $provided
     * @return bool
     */
    public function isNewerThan(DateTime $provided): bool
    {
        return $this->dateTime > $provided;
    }

    /**
     * Is the given DateTime older than or equal to $provided DateTime
     *
     * @param DateTime $provided
     * @return bool
     */
    public function isOlderThanOrEqualTo(DateTime $provided): bool
    {
        return $this->dateTime <= $provided;
    }

    /**
     * Is the given DateTime newer than or equal to $provided DateTime
     *
     * @param DateTime $provided
     * @return bool
     */
    public function isNewerThanOrEqualTo(DateTime $provided): bool
    {
        return $this->dateTime >= $provided;
    }

    /**
     * Is the given DateTime hour less than $provided hour number. Uses 24 hour numbers.
     *
     * @param int $provided
     * @return bool
     */
    public function isHourLessThan(int $provided): bool
    {
        return intval($this->dateTime->format('G')) < $provided;
    }

    /**
     * Is the given DateTime hour greater than $provided hour number. Uses 24 hour numbers.
     *
     * @param int $provided
     * @return bool
     */
    public function isHourGreaterThan(int $provided): bool
    {
        return intval($this->dateTime->format('G')) > $provided;
    }

    /**
     * Is the given DateTime hour less than or equal to than $provided hour number. Uses 24 hour numbers.
     *
     * @param int $provided
     * @return bool
     */
    public function isHourLessThanOrEqualTo(int $provided): bool
    {
        return intval($this->dateTime->format('G')) <= $provided;
    }

    /**
     * Is the given DateTime hour greater than or equal to $provided hour number. Uses 24 hour numbers.
     *
     * @param int $provided
     * @return bool
     */
    public function isHourGreaterThanOrEqualTo(int $provided): bool
    {
        return intval($this->dateTime->format('G')) >= $provided;
    }

    /**
     * @return bool
     */
    public function isJanuary(): bool
    {
        return $this->isMonthNumberX(self::MONTH_JANUARY);
    }

    /**
     * @return bool
     */
    public function isFebruary(): bool
    {
        return $this->isMonthNumberX(self::MONTH_FEBRUARY);
    }

    /**
     * @return bool
     */
    public function isMarch(): bool
    {
        return $this->isMonthNumberX(self::MONTH_MARCH);
    }

    /**
     * @return bool
     */
    public function isApril(): bool
    {
        return $this->isMonthNumberX(self::MONTH_APRIL);
    }

    /**
     * @return bool
     */
    public function isMay(): bool
    {
        return $this->isMonthNumberX(self::MONTH_MAY);
    }

    /**
     * @return bool
     */
    public function isJune(): bool
    {
        return $this->isMonthNumberX(self::MONTH_JUNE);
    }

    /**
     * @return bool
     */
    public function isJuly(): bool
    {
        return $this->isMonthNumberX(self::MONTH_JULY);
    }

    /**
     * @return bool
     */
    public function isAugust(): bool
    {
        return $this->isMonthNumberX(self::MONTH_AUGUST);
    }

    /**
     * @return bool
     */
    public function isSeptember(): bool
    {
        return $this->isMonthNumberX(self::MONTH_SEPTEMBER);
    }

    /**
     * @return bool
     */
    public function isOctober(): bool
    {
        return $this->isMonthNumberX(self::MONTH_OCTOBER);
    }

    /**
     * @return bool
     */
    public function isNovember(): bool
    {
        return $this->isMonthNumberX(self::MONTH_NOVEMBER);
    }

    /**
     * @return bool
     */
    public function isDecember(): bool
    {
        return $this->isMonthNumberX(self::MONTH_DECEMBER);
    }

    /**
     * @return bool
     */
    public function isMonday(): bool
    {
        return $this->isDayOfWeekX(self::DAY_MONDAY);
    }

    /**
     * @return bool
     */
    public function isTuesday(): bool
    {
        return $this->isDayOfWeekX(self::DAY_TUESDAY);
    }

    /**
     * @return bool
     */
    public function isWednesday(): bool
    {
        return $this->isDayOfWeekX(self::DAY_WEDNESDAY);
    }

    /**
     * @return bool
     */
    public function isThursday(): bool
    {
        return $this->isDayOfWeekX(self::DAY_THURSDAY);
    }

    /**
     * @return bool
     */
    public function isFriday(): bool
    {
        return $this->isDayOfWeekX(self::DAY_FRIDAY);
    }

    /**
     * @return bool
     */
    public function isSaturday(): bool
    {
        return $this->isDayOfWeekX(self::DAY_SATURDAY);
    }

    /**
     * @return bool
     */
    public function isSunday(): bool
    {
        return $this->isDayOfWeekX(self::DAY_SUNDAY);
    }

    /**
     * Is the given DateTime a leap year?
     *
     * @return bool
     */
    public function isLeapYear(): bool
    {
        $y = intval($this->dateTime->format('Y'));
        return is_integer($y / 4);
    }

    /**
     * Is a given date time a given day of the week?
     *
     * @param int $dayNumber (1 to 7)
     * @return bool
     */
    public function isDayOfWeekX(int $dayNumber): bool
    {
        return intval($this->dateTime->format('N')) === $dayNumber;
    }

    /**
     * Is a given date within a specific month number?
     *
     * @param int $monthNumber (1 to 12)
     * @return bool
     */
    public function isMonthNumberX(int $monthNumber): bool
    {
        return intval($this->dateTime->format('n')) === $monthNumber;
    }

    /**
     * @return bool
     */
    public function isToday(): bool
    {
        try {
            $now = new DateTime();
            return $now->format('Y-m-d') === $this->dateTime->format('Y-m-d');
        } catch (Exception $e) {
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isTomorrow(): bool
    {
        try {
            $now = new DateTime();
            $now->add(new DateInterval('P1D'));
            return $now->format('Y-m-d') === $this->dateTime->format('Y-m-d');
        } catch (Exception $e) {
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isYesterday(): bool
    {
        try {
            $now = new DateTime();
            $now->sub(new DateInterval('P1D'));
            return $now->format('Y-m-d') === $this->dateTime->format('Y-m-d');
        } catch (Exception $e) {
        }
        return false;
    }

    /**
     * Is the given DateTime between 2 DateTimes? Internally, this uses <= and >=.
     *
     * @param DateTime $firstDate
     * @param DateTime $lastDate
     * @return int
     */
    public function isBetween(DateTime $firstDate, DateTime $lastDate)
    {
        $dateOne = clone $firstDate;
        $dateTwo = clone $lastDate;
        $sorted = Date::sorter()->sortNewestToOldest($dateOne, $dateTwo);

        return $sorted[0] >= $this->dateTime && $sorted[1] <= $this->dateTime;
    }

    /**
     * Is the given DateTime the same date as this $otherDate?
     *
     * @param DateTime $otherDate
     * @return int
     */
    public function isSameDay(DateTime $otherDate)
    {
        return Date::output($this->dateTime)->toDMY() === Date::output($otherDate)->toDMY();
    }

    /**
     * @return bool
     */
    public function isFuture(): bool
    {
        return $this->isNewerThan(Date::nowDefault());
    }

    /**
     * @return bool
     */
    public function isPast(): bool
    {
        return $this->isOlderThan(Date::nowDefault());
    }

    /**
     * @return bool
     */
    public function isWeekend(): bool
    {
        return $this->isSaturday() || $this->isSunday();
    }

    /**
     * @return bool
     */
    public function isWeekday(): bool
    {
        return !$this->isWeekend();
    }
}
