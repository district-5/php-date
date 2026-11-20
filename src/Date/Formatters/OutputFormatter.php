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

namespace District5\Date\Formatters;

use DateTimeInterface;
use District5\Date\Abstracts\AbstractConstructor;

/**
 * Class OutputFormatter
 * @package District5\Date\Formatters
 */
class OutputFormatter extends AbstractConstructor
{
    /**
     * Get the day of the week as an ISO-8601 integer (1=Monday, 7=Sunday).
     *
     * @return int
     */
    public function getDayOfWeek(): int
    {
        return intval($this->toFormat('N'));
    }

    /**
     * Get the full English name of the day of the week (e.g. "Monday").
     *
     * @return string
     */
    public function getDayName(): string
    {
        return $this->toFormat('l');
    }

    /**
     * Get the full English name of the month (e.g. "January").
     *
     * @return string
     */
    public function getMonthName(): string
    {
        return $this->toFormat('F');
    }

    /**
     * Convert a DateTime to a combined date-time string (e.g. "2019-03-20 22:03:40").
     *
     * @param string $dateSeparator default '-' the separator between date components.
     * @param string $timeSeparator default ':' the separator between time components.
     * @return string
     */
    public function toYMDHIS(string $dateSeparator = '-', string $timeSeparator = ':'): string
    {
        return $this->toFormat(
            sprintf(
                'Y%sm%sd H%si%ss',
                self::escapeFormatLiteral($dateSeparator),
                self::escapeFormatLiteral($dateSeparator),
                self::escapeFormatLiteral($timeSeparator),
                self::escapeFormatLiteral($timeSeparator)
            )
        );
    }

    /**
     * Get the suffix for the day. For example, day 1 is 'st', day 2 is 'nd', day 3 is 'rd', day 4 is 'th
     * @return string
     */
    public function getDaySuffix(): string
    {
        return $this->toFormat('S');
    }

    /**
     * Get the year of the DateTime object as an integer.
     *
     * @return int
     */
    public function getYear(): int
    {
        return intval($this->toFormat('Y'));
    }

    /**
     * Get the month of the DateTime object as an integer.
     *
     * @return int
     */
    public function getMonth(): int
    {
        return intval($this->toFormat('m'));
    }

    /**
     * Get the week number of the DateTime object as an integer.
     *
     * @return int
     */
    public function getWeek(): int
    {
        return intval($this->toFormat('W'));
    }

    /**
     * Get the day of the DateTime object as an integer.
     *
     * @return int
     */
    public function getDay(): int
    {
        return intval($this->toFormat('d'));
    }

    /**
     * Get the 24-hour format of hour and minutes of the datetime in XX:XX format
     *
     * @return string
     */
    public function getHourMinutes(): string
    {
        return $this->toFormat('H:i');
    }

    /**
     * Get the 24-hour format of hour, minutes and seconds of the datetime in XX:XX:XX format
     *
     * @return string
     */
    public function getHourMinutesSeconds(): string
    {
        return $this->toFormat('H:i:s');
    }

    /**
     * Get the hour of the DateTime object as an integer. Uses 24-hour numbers.
     *
     * @return int
     */
    public function getHour(): int
    {
        return intval($this->toFormat('H'));
    }

    /**
     * Get the minutes of the DateTime object as an integer.
     *
     * @return int
     */
    public function getMinutes(): int
    {
        return intval($this->toFormat('i'));
    }

    /**
     * Get the seconds of the DateTime object as an integer.
     *
     * @return int
     */
    public function getSeconds(): int
    {
        return intval($this->toFormat('s'));
    }

    /**
     * Get the microseconds of the DateTime object as an integer.
     *
     * @return int
     */
    public function getMicroseconds(): int
    {
        return intval($this->toFormat('u'));
    }

    /**
     * Get the milliseconds of the DateTime object as an integer. Be aware that this is truncated, not
     * rounded (e.g. 123999 microseconds gives 123).
     *
     * @return int
     */
    public function getMilliseconds(): int
    {
        return intval($this->toFormat('v'));
    }

    /**
     * Convert a DateTime to a HH:MM:SS formatted string. Optionally change `$separator` to
     * use a separator other than the ':' sign.
     *
     * @param string $separator default ':' the separator to use.
     * @return string
     */
    public function toTimeHMS(string $separator = ':'): string
    {
        $sep = self::escapeFormatLiteral($separator);
        return $this->toFormat(sprintf('H%si%ss', $sep, $sep));
    }

    /**
     * Convert a DateTime to a HH:MM formatted string. Optionally change `$separator` to
     * use a separator other than the ':' sign.
     *
     * @param string $separator default ':' the separator to use.
     * @return string
     */
    public function toTimeHM(string $separator = ':'): string
    {
        return $this->toFormat(sprintf('H%si', self::escapeFormatLiteral($separator)));
    }

    /**
     * Convert a DateTime to YYYY-MM-DD or YYYYMMDD
     *
     * @param bool $useSeparators default true, whether to return YYYY-MM-DD or YYYYMMDD
     * @param string $separator default '-' the separator to use.
     * @return string
     */
    public function toYMD(bool $useSeparators = true, string $separator = '-'): string
    {
        if ($useSeparators === true) {
            $sep = self::escapeFormatLiteral($separator);
            return $this->toFormat(sprintf('Y%sm%sd', $sep, $sep));
        }
        return $this->toFormat('Ymd');
    }

    /**
     * Convert a DateTime to DD-MM-YYYY or DDMMYYYY
     *
     * @param bool $useSeparators default true, whether to return DD-MM-YYYY or DDMMYYYY
     * @param string $separator default '-' the separator to use.
     * @return string
     */
    public function toDMY(bool $useSeparators = true, string $separator = '-'): string
    {
        if ($useSeparators === true) {
            $sep = self::escapeFormatLiteral($separator);
            return $this->toFormat(sprintf('d%sm%sY', $sep, $sep));
        }
        return $this->toFormat('dmY');
    }

    /**
     * Convert a DateTime to MM-DD-YYYY or MMDDYYYY
     *
     * @param bool $useSeparators default true, whether to return MM-DD-YYYY or MMDDYYYY
     * @param string $separator default '-' the separator to use.
     * @return string
     */
    public function toMDY(bool $useSeparators = true, string $separator = '-'): string
    {
        if ($useSeparators === true) {
            $sep = self::escapeFormatLiteral($separator);
            return $this->toFormat(sprintf('m%sd%sY', $sep, $sep));
        }
        return $this->toFormat('mdY');
    }

    /**
     * @param bool $asString default false, whether to return a string.
     * @return int|string
     * @see OutputFormatter::toUnixTimestamp()
     */
    public function toTimestamp(bool $asString = false): int|string
    {
        return $this->toUnixTimestamp($asString);
    }

    /**
     * Convert a DateTime to unix timestamp
     *
     * @param bool $asString default false, whether to return a string.
     * @return int|string
     */
    public function toUnixTimestamp(bool $asString = false): int|string
    {
        $v = $this->toFormat('U');
        if ($asString === true) {
            return $v;
        }
        return intval($v);
    }

    /**
     * Convert a DateTime to {seconds}.{microsecond} format.
     * Pre-1970 values are signed decimals, e.g. 1969-12-31 23:59:59.5 is -0.5 / '-0.500000'.
     *
     * @param bool $asString default false, whether to return a string.
     * @return float|string
     */
    public function toMicrosecondTimestamp(bool $asString = false): float|string
    {
        return $this->toFractionalTimestamp(intval($this->toFormat('u')), 1000000, 6, $asString);
    }

    /**
     * Convert a DateTime to {seconds}.{millisecond} format. Milliseconds are truncated, not rounded.
     * Pre-1970 values are signed decimals, e.g. 1969-12-31 23:59:59.500 is -0.5 / '-0.500'.
     *
     * @param bool $asString default false, whether to return a string.
     * @return float|string
     */
    public function toMillisecondTimestamp(bool $asString = false): float|string
    {
        return $this->toFractionalTimestamp(intval($this->toFormat('v')), 1000, 3, $asString);
    }

    /**
     * Build a signed decimal seconds value from the whole-second timestamp plus a non-negative fraction.
     *
     * @param int $fraction the fractional part, in units of 1/$unit seconds (always >= 0)
     * @param int $unit
     * @param int $digits
     * @param bool $asString
     * @return float|string
     */
    private function toFractionalTimestamp(int $fraction, int $unit, int $digits, bool $asString): float|string
    {
        $total = $this->dateTime->getTimestamp() * $unit + $fraction;
        if ($asString === false) {
            return floatval($total / $unit);
        }
        $abs = abs($total);
        return sprintf(
            '%s%d.%0' . $digits . 'd',
            $total < 0 ? '-' : '',
            intdiv($abs, $unit),
            $abs % $unit
        );
    }

    /**
     * Escape every character of a string so that DateTime::format() outputs it literally.
     *
     * @param string $value
     * @return string
     */
    private static function escapeFormatLiteral(string $value): string
    {
        if ($value === '') {
            return '';
        }
        return '\\' . implode('\\', str_split($value));
    }

    /**
     * @return string
     */
    public function toISO8601(): string
    {
        return $this->toFormat(DateTimeInterface::ATOM);
    }

    /**
     * Output this DateTime to a specific format
     *
     * @param string $format The format to use
     * @return string
     */
    public function toFormat(string $format): string
    {
        return $this->dateTime->format(
            $format
        );
    }
}
