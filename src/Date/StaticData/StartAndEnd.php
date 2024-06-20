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
 * Class StartAndEnd
 * @package District5\Date\StaticData
 */
class StartAndEnd
{
    /**
     * @param int $year
     * @param int $month
     * @param int $day
     * @return DateTime
     */
    public function startOfDayFromYearMonthDay(int $year, int $month, int $day): DateTime
    {
        return $this->startOfMonthFromDateTime(
            Date::createYMDHISM($year, $month, $day, 0, 0, 0, 0)
        );
    }

    /**
     * @param DateTime $dateTime
     * @return DateTime
     */
    public function startOfDayFromDateTime(DateTime $dateTime): DateTime
    {
        return Date::modify($dateTime)->setTime(0, 0, 0, 0);
    }

    /**
     * @param int $year
     * @param int $month
     * @param int $day
     * @return DateTime
     */
    public function endOfDayFromYearMonthDay(int $year, int $month, int $day): DateTime
    {
        return $this->endOfMonthFromDateTime(
            Date::createYMDHISM($year, $month, $day, 0, 0, 0, 0)
        );
    }

    /**
     * @param DateTime $dateTime
     * @return DateTime
     */
    public function endOfDayFromDateTime(DateTime $dateTime): DateTime
    {
        return Date::modify($dateTime)->setTime(23, 59, 59, 999999);
    }

    /**
     * @param int $year
     * @param int $month
     * @return DateTime
     */
    public function startOfMonthFromYearMonth(int $year, int $month): DateTime
    {
        return $this->startOfMonthFromDateTime(
            Date::createYMDHISM($year, $month, 1, 0, 0, 0, 0)
        );
    }

    /**
     * @param DateTime $dateTime
     * @return DateTime
     */
    public function startOfMonthFromDateTime(DateTime $dateTime): DateTime
    {
        return Date::modify(
            Date::modify($dateTime)->setDate(1)
        )->setTime(0, 0, 0, 0);
    }

    /**
     * @param int $year
     * @param int $month
     * @return DateTime
     */
    public function endOfMonthFromYearMonth(int $year, int $month): DateTime
    {
        return $this->endOfMonthFromDateTime(
            Date::createYMDHISM($year, $month, 1, 0, 0, 0, 0)
        );
    }

    /**
     * @param DateTime $dateTime
     * @return DateTime
     */
    public function endOfMonthFromDateTime(DateTime $dateTime): DateTime
    {
        $start = $this->startOfMonthFromDateTime($dateTime);
        return Date::modify(
            $start
        )->setDate(
            Date::month()->numberDaysInMonth(intval($start->format('n')), intval($start->format('Y')))
        )->setTime(23, 59, 59, 999999);
    }

    /**
     * @param int $year
     * @return DateTime
     */
    public function startOfYearFromYear(int $year): DateTime
    {
        return $this->startOfMonthFromDateTime(
            Date::createYMDHISM($year, 1, 1, 0, 0, 0, 0)
        );
    }

    /**
     * @param DateTime $dateTime
     * @return DateTime
     */
    public function startOfYearFromDateTime(DateTime $dateTime): DateTime
    {
        return $this->startOfMonthFromDateTime(
            Date::createYMDHISM(intval($dateTime->format('Y')), 1, 1, 0, 0, 0, 0)
        );
    }

    /**
     * @param int $year
     * @return DateTime
     */
    public function endOfYearFromYear(int $year): DateTime
    {
        return $this->endOfMonthFromDateTime(
            Date::createYMDHISM($year, 12, 1, 0, 0, 0, 0)
        );
    }

    /**
     * @param DateTime $dateTime
     * @return DateTime
     */
    public function endOfYearFromDateTime(DateTime $dateTime): DateTime
    {
        return $this->endOfMonthFromDateTime(
            Date::createYMDHISM(intval($dateTime->format('Y')), 12, 31, 0, 0, 0, 0)
        );
    }

    /**
     * @return DateTime
     */
    public function startOfToday(): DateTime
    {
        $date = Date::nowDefault();
        return $this->startOfDayFromDateTime($date);
    }

    /**
     * @return DateTime
     */
    public function endOfToday(): DateTime
    {
        $date = Date::nowDefault();
        return $this->endOfDayFromDateTime($date);
    }
}
