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
     * @param int $monthNumber
     * @param int|null $year
     * @return DateTime
     */
    public function firstDateInMonth(int $monthNumber, int $year = null): DateTime
    {
        if ($year === null) {
            $year = Date::output(Date::nowDefault())->getYear();
        }
        return Date::createYMDHISM($year, $monthNumber, 1);
    }

    /**
     * @return DateTime
     * @noinspection PhpUnused
     */
    public function lastDateInCurrentMonth(): DateTime
    {
        $now = Date::nowDefault();
        $output = Date::output($now);
        return Date::createYMDHISM(
            $output->getYear(),
            $output->getMonth(),
            1,
            23,
            59,
            59,
            999999
        );
    }

    /**
     * @param int $monthNumber
     * @param int|null $year
     * @return int
     */
    public function numberDaysInMonth(int $monthNumber, int $year = null): int
    {
        return Date::output(
            self::lastDateInMonth(
                $monthNumber,
                $year
            )
        )->getDay();
    }

    /**
     * @param int $monthNumber
     * @param int|null $year
     * @return DateTime
     */
    public function lastDateInMonth(int $monthNumber, int $year = null): DateTime
    {
        if ($year === null) {
            $year = Date::output(Date::nowDefault())->getYear();
        }
        $date = Date::createYMDHISM($year, $monthNumber, 1);
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
}
