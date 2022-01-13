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

namespace District5\Utility;

/**
 * Date
 *
 * A utility for working with Dates
 */
class Date
{

    /**
     * Calculates the number of days in the given month and year
     *
     * @param int $month The month
     * @param int $year The year (Optional) [Default: current year]
     *
     * @return int The number of days in the given month of the given year
     */
    public static function CalculateDaysInMonth($month, $year = null)
    {
        if (null === $year)
        {
            $now = new \DateTime();
            $year = date('Y', $now->getTimestamp());
        }

        return cal_days_in_month(CAL_GREGORIAN, $month, $year);
    }

    /**
     * Calculates how many days are left in a month given the current day, the month and year to check for
     *
     * @param int $currentDay The day to check from
     * @param int $month The month
     * @param int $year The year (Optional) [Default: current year]
     *
     * @return int The number days left in the given month
     */
    public static function CalculateDaysLeftInGivenMonth($currentDay, $month, $year = null)
    {
        $daysInMonth = static::CalculateDaysInMonth($month, $year);

        if ($currentDay > $daysInMonth)
        {
            throw new \InvalidArgumentException('Invalid number of days, number of days exceeds the number of days in the given month');
        }

        return $daysInMonth - $currentDay;
    }

    /**
     * Converts a Unix timestamp from Utc to an AARK supported timezone with the given id
     *
     * @param int|string $utcTimestamp The Utc timestamp
     * @param string $toTimezoneId The AARK timezone id
     *
     * @return int The converted timestamp
     */
    public static function ConvertFromUtc($utcTimestamp, $toTimezoneId)
    {
        $timezone = Timezone::GetTimezone($toTimezoneId);
        if (null === $timezone)
        {
            throw new \InvalidArgumentException('Invalid timezoneId specified in Date::ConvertFromUtc()');
        }

        $hourOffset = $timezone['utcOffset'];

        // TODO: check this should have the inner strtotime(), if its already a timestamp it shouldn't be needed
        if ($hourOffset >= 0)
        {
            return strtotime(date("Y-m-d H:i:s", strtotime($utcTimestamp)) . " +" . $hourOffset . " hours");
        }
        else
        {
            return strtotime(date("Y-m-d H:i:s", strtotime($utcTimestamp)) . " -" . abs($hourOffset) . " hours");
        }
    }

    /**
     * Converts a timestamp in a timezone with the given id to a Utc Unix timestamp
     *
     * @param int|string $timezoneTimestamp The current timezone timestamp
     * @param string $fromTimezoneId The AARK timezone id
     *
     * @return int The converted timestamp
     */
    public static function ConvertToUtc($timezoneTimestamp, $fromTimezoneId)
    {
        $timezone = Timezone::GetTimezone($fromTimezoneId);
        if (null === $timezone)
            throw new \InvalidArgumentException('Invalid timezoneId specified in Date::ConvertToUtc()');

        $hourOffset = $timezone['utcOffset'];

        if ($hourOffset >= 0)
            return strtotime(date("Y-m-d H:i:s", strtotime($timezoneTimestamp)) . " -" . $hourOffset . " hours");
        else
            return strtotime(date("Y-m-d H:i:s", strtotime($timezoneTimestamp)) . " +" . abs($hourOffset) . " hours");
    }

    /**
     * Gets a DateTime of a given DateTime plus the number of seconds specified
     *
     * @param \DateTime $date The DateTime to base calculations from
     * @param int $x The number of seconds
     *
     * @return \DateTime A DateTime of the given DateTime plus the number of seconds specified
     */
    public static function DatePlusXSeconds(\DateTime $date, $x)
    {
        return static::_DatePlusXTimePeriod($date, $x, 'seconds');
    }

    /**
     * Gets a DateTime of a given DateTime plus the number of minutes specified
     *
     * @param \DateTime $date The DateTime to base calculations from
     * @param int $x The number of minutes
     *
     * @return \DateTime A DateTime of the given DateTime plus the number of minutes specified
     */
    public static function DatePlusXMinutes(\DateTime $date, $x)
    {
        return static::_DatePlusXTimePeriod($date, $x, 'minutes');
    }

    /**
     * Gets a DateTime of a given DateTime plus the number of hours specified
     *
     * @param \DateTime $date The DateTime to base calculations from
     * @param int $x The number of hours
     *
     * @return \DateTime A DateTime of the given DateTime plus the number of hours specified
     */
    public static function DatePlusXHours(\DateTime $date, $x)
    {
        return static::_DatePlusXTimePeriod($date, $x, 'hours');
    }

    /**
     * Gets a DateTime of a given DateTime plus the number of days specified
     *
     * @param \DateTime $date The DateTime to base calculations from
     * @param int $x The number of days
     *
     * @return \DateTime A DateTime of the given DateTime plus the number of days specified
     */
    public static function DatePlusXDays(\DateTime $date, $x)
    {
        return static::_DatePlusXTimePeriod($date, $x, 'days');
    }

    /**
     * Gets a DateTime of a given DateTime plus the number of months specified
     *
     * @param \DateTime $date The DateTime to base calculations from
     * @param int $x The number of months
     *
     * @return \DateTime A DateTime of the given DateTime plus the number of months specified
     */
    public static function DatePlusXMonths(\DateTime $date, $x)
    {
        return static::_DatePlusXTimePeriod($date, $x, 'months');
    }

    /**
     * Gets a DateTime of a given DateTime plus the number of years specified
     *
     * @param \DateTime $date The DateTime to base calculations from
     * @param int $x The number of years
     *
     * @return \DateTime A DateTime of the given DateTime plus the number of years specified
     */
    public static function DatePlusXYears(\DateTime $date, $x)
    {
        return static::_DatePlusXTimePeriod($date, $x, 'years');
    }

    /**
     * Gets a DateTime of a given DateTime plus the number of specified interval periods
     *
     * @param \DateTime $date The DateTime to base calculations from
     * @param int $x The number of interval periods
     * @param string $timePeriod The time period to use for intervals
     *
     * @return \DateTime A DateTime of the given DateTime plus the number of time intervals specified
     */
    protected static function _DatePlusXTimePeriod(\DateTime $date, $x, $timePeriod)
    {
        $endDate = strtotime(date("Y-m-d H:i:s", $date->getTimestamp()) . " +" . $x . " " . $timePeriod);

        $dt = new \DateTime();
        $dt->setTimestamp($endDate);

        return $dt;
    }

    /**
     * Gets the 2 character english suffix for a number
     *
     * @param int|string $number The number to generate a suffix for
     *
     * @return string The 2 digit english suffix ('st', 'nd', 'rd' or 'th')
     */
    public static function GetNumberSuffix($number)
    {
        $lastDigit = substr((string)($number), -1);
        switch ($lastDigit)
        {
            case "1":
                return 'st';
            case "2":
                return 'nd';
            case "3":
                return 'rd';
            default:
                return 'th';
        }
    }

    /**
     * Gets the number of seconds since the epoch (Unix Timestamp)
     *
     * @return int
     */
    public static function GetSecondsSinceEpoch()
    {
        return time();
    }

    /**
     * Checks whether dateStr1 is after dateStr2 utilising strtotime() to calculate dates
     *
     * @param string $dateStr1
     * @param string $dateStr2
     *
     * @return bool True if dateStr1 is after dateStr2, false otherwise
     */
    public static function IsDateAfterDate($dateStr1, $dateStr2)
    {
        return strtotime($dateStr1) > strtotime($dateStr2);
    }

    /**
     * Checks whether dateStr1 is before dateStr2 utilising strtotime() to calculate dates
     *
     * @param string $dateStr1
     * @param string $dateStr2
     *
     * @return bool True if dateStr1 is before dateStr2, false otherwise
     */
    public static function IsDateBeforeDate($dateStr1, $dateStr2)
    {
        return strtotime($dateStr1) < strtotime($dateStr2);
    }

    /**
     * Checks whether date1 and date2 are 'on the same day'. Fast fails on the day, followed by month, finally year
     *
     * @param \DateTime $date1
     * @param \DateTime $date2
     *
     * @return bool
     */
    public static function IsSameDay(\DateTime $date1, \DateTime $date2) : bool
    {
        if ((int)($date1->format('d')) !== (int)($date2->format('d')))
        {
            // days dont match
            return false;
        }

        if ((int)($date1->format('m')) !== (int)($date2->format('m')))
        {
            // months dont match
            return false;
        }

        if ((int)($date1->format('Y')) !== (int)($date2->format('Y')))
        {
            // years dont match
            return false;
        }

        return true;
    }

    /**
     * Gets a DateTime of the current time minus the number of seconds specified
     *
     * @param int $x The number of seconds
     *
     * @return \DateTime A DateTime of the current time minus the number of seconds specified
     */
    public static function NowMinusXSeconds($x)
    {
        return static::_NowMinusXTimePeriod($x, 'seconds');
    }

    /**
     * Gets a DateTime of the current time minus the number of minutes specified
     *
     * @param int $x The number of minutes
     *
     * @return \DateTime A DateTime of the current time minus the number of minutes specified
     */
    public static function NowMinusXMinutes($x)
    {
        return static::_NowMinusXTimePeriod($x, 'minutes');
    }

    /**
     * Gets a DateTime of the current time minus the number of hours specified
     *
     * @param int $x The number of hours
     *
     * @return \DateTime A DateTime of the current time minus the number of hours specified
     */
    public static function NowMinusXHours($x)
    {
        return static::_NowMinusXTimePeriod($x, 'hours');
    }

    /**
     * Gets a DateTime of the current time
     *
     * @return \DateTime A DateTime of the current time
     */
    public static function Now()
    {
        return new \DateTime();
    }

    /**
     * Gets a DateTime of the current time minus the number of days specified
     *
     * @param int $x The number of days
     *
     * @return \DateTime A DateTime of the current time minus the number of days specified
     */
    public static function NowMinusXDays($x)
    {
        return static::_NowMinusXTimePeriod($x, 'days');
    }

    /**
     * Gets a DateTime of the current time minus the number of months specified
     *
     * @param int $x The number of months
     *
     * @return \DateTime A DateTime of the current time minus the number of months specified
     */
    public static function NowMinusXMonths($x)
    {
        return static::_NowMinusXTimePeriod($x, 'months');
    }

    /**
     * Takes the current DateTime, DateTime('now'), and subtracts the given interval
     *
     * @param int $x The amount of time to subtract
     * @param string $timePeriod The time period, e.g. seconds, minutes, days etc
     *
     * @return \DateTime The adjusted time period
     */
    protected static function _NowMinusXTimePeriod($x, $timePeriod)
    {
        $date = date("Y-m-d H:i:s");

        $date = strtotime(date("Y-m-d H:i:s", strtotime($date)) . " -" . $x . "  " . $timePeriod);

        $dt = new \DateTime();
        $dt->setTimestamp($date);

        return $dt;
    }

    /**
     * Gets a DateTime of the current time plus the number of seconds specified
     *
     * @param int $x The number of seconds
     *
     * @return \DateTime A DateTime of the current time plus the number of seconds specified
     */
    public static function NowPlusXSeconds($x)
    {
        return static::_NowPlusXTimePeriod($x, 'seconds');
    }

    /**
     * Gets a DateTime of the current time plus the number of minutes specified
     *
     * @param int $x The number of minutes
     *
     * @return \DateTime A DateTime of the current time plus the number of minutes specified
     */
    public static function NowPlusXMinutes($x)
    {
        return static::_NowPlusXTimePeriod($x, 'minutes');
    }

    /**
     * Gets a DateTime of the current time plus the number of hours specified
     *
     * @param int $x The number of hours
     *
     * @return \DateTime A DateTime of the current time plus the number of hours specified
     */
    public static function NowPlusXHours($x)
    {
        return static::_NowPlusXTimePeriod($x, 'hours');
    }

    /**
     * Gets a DateTime of the current time plus the number of days specified
     *
     * @param int $x The number of days
     *
     * @return \DateTime A DateTime of the current time plus the number of days specified
     */
    public static function NowPlusXDays($x)
    {
        return static::_NowPlusXTimePeriod($x, 'days');
    }

    /**
     * Gets a DateTime of the current time plus the number of months specified
     *
     * @param int $x The number of months
     *
     * @return \DateTime A DateTime of the current time plus the number of months specified
     */
    public static function NowPlusXMonths($x)
    {
        return static::_NowPlusXTimePeriod($x, 'months');
    }

    /**
     * @param $x
     * @param $timePeriod
     * @return \DateTime
     */
    protected static function _NowPlusXTimePeriod($x, $timePeriod)
    {
        $date = date("Y-m-d H:i:s");

        $date = strtotime(date("Y-m-d H:i:s", strtotime($date)) . " +" . $x . "  " . $timePeriod);

        $dt = new \DateTime();
        $dt->setTimestamp($date);

        return $dt;
    }

    /**
     * Gets a DateTime given a string representation
     *
     * @param String $dateString The string should be in the format "yyyy-mm-dd hh:mm:ss"
     *
     * @return \DateTime A DateTime of the specified date string
     */
    public static function SpecificDate($dateString)
    {
        $dt = new \DateTime();
        $dt->setTimestamp(strtotime($dateString));

        return $dt;
    }

    /**
     * Gets a DateTime given a timestamp
     *
     * @param int $timestamp The timestamp
     *
     * @return \DateTime A DateTime of the specified date timestamp
     */
    public static function SpecificDateFromTimestamp($timestamp)
    {
        $dt = new \DateTime();
        $dt->setTimestamp($timestamp);

        return $dt;
    }

    /**
     * Gets a string representation of a non-leading zero day of a DateTime
     *
     * @param \DateTime $date
     *
     * @return string
     */
    public static function ToDayString($date)
    {
        return self::_DateToString($date, 'j');
    }

    /**
     * Gets a string representation of a leading zero day followed by 3 letter month (e.g. 16 Apr) of a DateTime
     *
     * @param \DateTime $date
     *
     * @return string
     */
    public static function ToDayMonthString($date)
    {
        return self::_DateToString($date, 'd M');
    }

    /**
     * Gets a string representation of a year month day of a DateTime, in the format yyyy-mm-dd
     *
     * @param \DateTime $date
     *
     * @return string
     */
    public static function ToYearMonthDayString($date)
    {
        return self::_DateToString($date, 'Y-m-d');
    }

    /**
     * Gets a string representation of a non-leading zero month of a DateTime
     *
     * @param \DateTime $date
     *
     * @return string
     */
    public static function ToMonthNumericString($date)
    {
        return self::_DateToString($date, 'n');
    }

    /**
     * Gets a string representation of a full 4 digit year
     *
     * @param \DateTime $date
     *
     * @return string
     */
    public static function ToYearString($date)
    {
        return self::_DateToString($date, 'Y');
    }

    public static function ToReallyPrettyDateString($date)
    {
        return self::_DateToString($date, 'l jS F, Y');
    }

    /**
     * Gets a 'pretty' human readable string representation of a DateTime
     *
     * @param \DateTime $date
     *
     * @return string The DateTime formatted in a human readable string
     */
    public static function ToPrettyDateTimeString($date)
    {
        return self::_DateToString($date, 'D jS M, Y (H:i)');
    }

    public static function ToTime24Hour($date)
    {
        return self::_DateToString($date, 'H:i');
    }

    /**
     * Formats a date as a string with the given format
     *
     * @param \DateTime $date The DateTime to format
     * @param string $format The format
     *
     * @return string The formatted date string
     */
    protected static function _DateToString($date, $format)
    {
        return date($format, $date->getTimestamp());
    }
}