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

namespace District5\Date\StaticData;

use DateTime;
use District5\Date\Date;
use InvalidArgumentException;

/**
 * Class Month
 * @package District5\Date\StaticData
 */
class Month
{
    /**
     * @return DateTime
     * @noinspection PhpUnused
     */
    public function firstDateInCurrentMonth(): DateTime
    {
        $now = Date::nowDefault();
        return self::firstDateInMonthFromDateTime($now);
    }

    /**
     * @param DateTime $provided
     * @return DateTime
     */
    public function firstDateInMonthFromDateTime(DateTime $provided): DateTime
    {
        $tmp = Date::modify($provided)->setDate(1);
        return Date::modify($tmp)->setTime(0, 0);
    }

    /**
     * @param int $monthNumber 1 (January) to 12 (December)
     * @param int|null $year
     * @return DateTime
     * @throws InvalidArgumentException if $monthNumber is not between 1 and 12, or the date cannot be created
     */
    public function firstDateInMonth(int $monthNumber, ?int $year = null): DateTime
    {
        if ($year === null) {
            $year = Date::output(Date::nowDefault())->getYear();
        }
        return $this->createDate($year, $monthNumber, 1);
    }

    /**
     * @return DateTime
     * @noinspection PhpUnused
     */
    public function lastDateInCurrentMonth(): DateTime
    {
        $now = Date::nowDefault();
        $output = Date::output($now);
        $year = $output->getYear();
        $month = $output->getMonth();
        return $this->createDate(
            $year,
            $month,
            $this->numberDaysInMonth($month, $year),
            23,
            59,
            59,
            999999
        );
    }

    /**
     * @param int $monthNumber 1 (January) to 12 (December)
     * @param int|null $year
     * @return int
     * @throws InvalidArgumentException if $monthNumber is not between 1 and 12, or the date cannot be created
     */
    public function numberDaysInMonth(int $monthNumber, ?int $year = null): int
    {
        return Date::output(
            self::lastDateInMonth(
                $monthNumber,
                $year
            )
        )->getDay();
    }

    /**
     * @param int $monthNumber 1 (January) to 12 (December)
     * @param int|null $year
     * @return DateTime
     * @throws InvalidArgumentException if $monthNumber is not between 1 and 12, or the date cannot be created
     */
    public function lastDateInMonth(int $monthNumber, ?int $year = null): DateTime
    {
        if ($year === null) {
            $year = Date::output(Date::nowDefault())->getYear();
        }
        $date = $this->createDate($year, $monthNumber, 1);
        $nextMonth = Date::modify($date)->plus()->months(1);
        return Date::modify($nextMonth)->minus()->days(1);
    }

    /**
     * @param DateTime $provided
     * @return int
     */
    public function numberDaysInMonthFromDateTime(DateTime $provided): int
    {
        return Date::output(
            self::lastDateInMonthFromDateTime($provided)
        )->getDay();
    }

    /**
     * @param DateTime $provided
     * @return DateTime
     */
    public function lastDateInMonthFromDateTime(DateTime $provided): DateTime
    {
        $output = Date::output($provided);
        return self::lastDateInMonth(
            $output->getMonth(),
            $output->getYear()
        );
    }

    /**
     * Create a DateTime in the default timezone, rejecting invalid month numbers rather than rolling them over.
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @param int $hour
     * @param int $minute
     * @param int $second
     * @param int $microsecond
     * @return DateTime
     * @throws InvalidArgumentException
     */
    private function createDate(int $year, int $month, int $day, int $hour = 0, int $minute = 0, int $second = 0, int $microsecond = 0): DateTime
    {
        if ($month < 1 || $month > 12) {
            throw new InvalidArgumentException(
                sprintf('Month number must be between 1 and 12, %d given.', $month)
            );
        }
        $date = Date::createYMDHISM($year, $month, $day, $hour, $minute, $second, $microsecond);
        if (!$date instanceof DateTime) {
            throw new InvalidArgumentException(
                sprintf('Unable to create date for %04d-%02d-%02d.', $year, $month, $day)
            );
        }
        return $date;
    }
}
