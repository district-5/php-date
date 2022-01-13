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

namespace District5\Date;

use DateTime;
use District5\Date\Calculators\Calculate;
use District5\Date\Calculators\Diff;
use District5\Date\Calculators\NtpServer;
use District5\Date\Converters\MongoConverter;
use District5\Date\Formatters\InputFormatter;
use District5\Date\Formatters\OutputFormatter;
use District5\Date\Formatters\Sorter;
use District5\Date\Manipulators\Modify;
use District5\Date\StaticData\Month;
use District5\Date\StaticData\Recurring;
use District5\Date\Tz\NowTimezone;
use District5\Date\Tz\Timezone;
use District5\Date\Validators\DateTimesValidator;
use District5\Date\Validators\DateTimeValidator;
use District5\Date\Validators\StringFormatValidator;

/**
 * Class Date
 * @package District5\Date
 */
class Date
{
    /**
     * Retrieve an instance of the OutputFormatter.
     * If you don't provide a DateTime, the Date::nowDefault() is used.
     *
     * @param DateTime|null $dateTime
     * @return OutputFormatter
     */
    public static function output(DateTime $dateTime = null): OutputFormatter
    {
        $dateTime = self::getDateTime($dateTime);
        return new OutputFormatter($dateTime);
    }

    /**
     * Retrieve an instance of Diff
     * If you don't provide a DateTime, the Date::nowDefault() is used.
     *
     * @param DateTime|null $dateTime
     * @return Diff
     */
    public static function diff(DateTime $dateTime = null): Diff
    {
        $dateTime = self::getDateTime($dateTime);
        return new Diff($dateTime);
    }

    /**
     * Retrieve an instance of the Calculate.
     * If you don't provide a DateTime, the Date::nowDefault() is used.
     *
     * @param DateTime|null $dateTime
     * @return Calculate
     */
    public static function calculate(DateTime $dateTime = null): Calculate
    {
        $dateTime = self::getDateTime($dateTime);
        return new Calculate($dateTime);
    }

    /**
     * Retrieve an instance of the Modify.
     * If you don't provide a DateTime, the Date::nowDefault() is used.
     *
     * @param DateTime|null $dateTime
     * @param bool $cloneDateTime (optional) default true, whether to make a clone or alter the instance in place.
     * @return Modify
     */
    public static function modify(DateTime $dateTime = null, bool $cloneDateTime = true): Modify
    {
        $dateTime = self::getDateTime($dateTime);
        return new Modify($dateTime, $cloneDateTime);
    }

    /**
     * Retrieve an instance of the InputFormatter.
     *
     * @param string|int|float $input
     * @return InputFormatter
     */
    public static function input($input): InputFormatter
    {
        return new InputFormatter($input);
    }

    /**
     * Retrieve an instance of the Recurring.
     *
     * @return Recurring
     */
    public static function recurring(): Recurring
    {
        return new Recurring();
    }

    /**
     * Retrieve an instance of the StringFormatValidator.
     *
     * @param string $input
     * @return StringFormatValidator
     */
    public static function validateString(string $input): StringFormatValidator
    {
        return new StringFormatValidator($input);
    }

    /**
     * Retrieve an instance of the DateTimesValidator.
     *
     * @param DateTime[] $dateTimes
     * @return DateTimesValidator
     */
    public static function validateArray(array $dateTimes): DateTimesValidator
    {
        return new DateTimesValidator($dateTimes);
    }

    /**
     * Retrieve an instance of the DateTimeValidator.
     * If you don't provide a DateTime, the Date::nowDefault() is used.
     *
     * @param DateTime $dateTime
     * @return DateTimeValidator
     */
    public static function validateObject(DateTime $dateTime): DateTimeValidator
    {
        return new DateTimeValidator($dateTime);
    }

    /**
     * Create an instance of DateTime by passing the exact date, time and timezone components.
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @param int $hour (optional), default 0
     * @param int $minute (optional), default 0
     * @param int $seconds (optional), default 0
     * @param int $microseconds (optional), default 0
     * @param string|null $timezone (optional), default null
     * @return DateTime|false
     */
    public static function createYMDHISM(int $year, int $month, int $day, int $hour = 0, int $minute = 0, int $seconds = 0, int $microseconds = 0, string $timezone = null)
    {
        if ($timezone === null) {
            $timezone = Date::getDefaultTimezone();
        }
        $date = Date::now()->inTimezone($timezone);
        if ($date === false) {
            return false;
        }

        if (false === Date::modify($date, false)->setDate($day, $month, $year)) {
            return false;
        }
        if (false === Date::modify($date, false)->setTime($hour, $minute, $seconds, $microseconds)) {
            return false;
        }

        return $date;
    }

    /**
     * Get the default now date. Uses the configured timezone as set in PHP.
     * 
     * @return DateTime
     */
    public static function nowDefault(): DateTime
    {
        return self::now()->default();
    }

    /**
     * Get the default now UTC date.
     *
     * @return DateTime
     */
    public static function nowUtc(): DateTime
    {
        return self::now()->utc();
    }

    /**
     * @return NowTimezone
     */
    public static function now(): NowTimezone
    {
        return new NowTimezone();
    }

    /**
     * Get an instance of Timezone. Used for converting dates between timezones.
     * If you don't provide a DateTime, the Date::nowDefault() is used.
     *
     * @param DateTime|null $dateTime
     * @return Timezone
     */
    public static function timezone(DateTime $dateTime = null): Timezone
    {
        $dateTime = self::getDateTime($dateTime);
        return new Timezone($dateTime);
    }

    /**
     * Retrieve an instance of sorter. Used for sorting arrays of DateTimes.
     *
     * @return Sorter
     */
    public static function sorter(): Sorter
    {
        return new Sorter();
    }

    /**
     * Retrieve an instance of MongoConverter. Used for converting between Mongo UTCDateTime and PHP DateTime
     *
     * @return MongoConverter
     */
    public static function mongo(): MongoConverter
    {
        return new MongoConverter();
    }

    /**
     * Get the default timezone name.
     *
     * @return string
     */
    public static function getDefaultTimezone(): string
    {
        return self::nowDefault()->getTimezone()->getName();
    }

    /**
     * Get age, in years for a date. Uses now()->default() to calculate the difference.
     *
     * @param DateTime $dateTime
     * @return int
     */
    public static function age(DateTime $dateTime): int
    {
        return self::diff($dateTime)->years(
            Date::now()->default()
        );
    }

    /**
     * Create a DateTime from a string.
     *
     * @param string $string
     * @return DateTime|false
     */
    public static function fromString(string $string)
    {
        if (false === $time = strtotime($string)) {
            return false;
        }
        return DateTime::createFromFormat('U', $time);
    }

    /**
     * Get the epoch in DateTime format.
     *
     * @return DateTime
     */
    public static function epoch(): DateTime
    {
        return DateTime::createFromFormat(
            'Y-m-d H:i:s.u',
            '1970-01-01 00:00:00.000'
        );
    }

    /**
     * @return int
     */
    public static function time(): int
    {
        return time();
    }

    /**
     * @param bool|null $asFloat
     * @return string|float
     */
    public static function microtime($asFloat = null)
    {
        return microtime($asFloat);
    }

    /**
     * Get an instance of Month.
     *
     * @return Month
     */
    public static function month(): Month
    {
        return new Month();
    }

    /**
     * Retrieve an instance of NtpServer. Used for reading a timestamp from an NTP server.
     *
     * @return NtpServer
     */
    public static function ntpServer(): NtpServer
    {
        return new NtpServer();
    }

    /**
     * @param DateTime|null $dateTime
     * @return DateTime|null
     */
    protected static function getDateTime(DateTime $dateTime = null): ?DateTime
    {
        if (null === $dateTime) {
            $dateTime = self::nowDefault();
        }
        return $dateTime;
    }
}
