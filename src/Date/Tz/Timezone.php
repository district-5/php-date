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

namespace District5\Date\Tz;

use DateTime;
use DateTimeZone;
use District5\Date\Abstracts\AbstractConstructor;
use Exception;

/**
 * Class Timezone
 * @package District5\Date\Tz
 */
class Timezone extends AbstractConstructor
{
    /**
     * Convert a datetime to a specific timezone. This method is obviously timezone aware based upon
     * the timezone contained within the original DateTime object.
     *
     * A string must be one of the identifiers in TzConstants. Any DateTimeZone object is accepted,
     * including offset based zones such as new DateTimeZone('+05:30').
     *
     * @param string|DateTimeZone $timezone
     * @return DateTime|false
     */
    public function toTimezone(string|DateTimeZone $timezone): DateTime|bool
    {
        $tz = $this->resolveTimezone($timezone);
        if ($tz === false) {
            return false;
        }
        $dt = clone $this->dateTime;
        $dt->setTimezone($tz);
        return $dt;
    }

    /**
     * Convert a datetime to a timezone from another timezone. The wall clock time of the DateTime (ignoring
     * its own timezone) is interpreted as being in $fromTimezone, and then converted to $toTimezone.
     *
     * @param string|DateTimeZone $toTimezone
     * @param string|DateTimeZone|null $fromTimezone (optional) - if not provided, the timezone of the DateTime is used.
     * @return DateTime|false false if either timezone is invalid.
     */
    public function toTimezoneFromTimezone(string|DateTimeZone $toTimezone, string|DateTimeZone|null $fromTimezone = null): DateTime|bool
    {
        $toTz = $this->resolveTimezone($toTimezone);
        if ($toTz === false) {
            return false;
        }
        $fromTz = $this->resolveTimezone($fromTimezone ?? $this->dateTime->getTimezone());
        if ($fromTz === false) {
            return false;
        }
        $newDt = DateTime::createFromFormat(
            'Y-m-d H:i:s.u',
            $this->dateTime->format('Y-m-d H:i:s.u'),
            $fromTz
        );
        if ($newDt === false) {
            return false;
        }
        $newDt->setTimezone($toTz);
        return $newDt;
    }

    /**
     * @param string|DateTimeZone $timezone
     * @return DateTimeZone|false
     */
    private function resolveTimezone(string|DateTimeZone $timezone): DateTimeZone|bool
    {
        if ($timezone instanceof DateTimeZone) {
            return clone $timezone;
        }
        if (TzConstants::isValidTimezone($timezone) === false) {
            return false;
        }
        try {
            return new DateTimeZone($timezone);
        } catch (Exception) {
        }
        return false;
    }

    /**
     * @return DateTimeZone
     * @throws Exception
     * @noinspection PhpUnused
     */
    public function getDefaultTimezone(): DateTimeZone
    {
        return new DateTimeZone(date_default_timezone_get());
    }
}
