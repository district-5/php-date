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
use District5\Date\Date;
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
     * @param string $timezone
     * @return DateTime|false
     */
    public function toTimezone(string $timezone): DateTime|bool
    {
        if (TzConstants::isValidTimezone($timezone) === false) {
            return false;
        }
        try {
            $dt = clone $this->dateTime;
            $dt->setTimezone(new DateTimeZone($timezone));
            return $dt;
        } catch (Exception) {
        }
        return false;
    }

    /**
     * Convert a datetime to a timezone from another timezone.
     *
     * @param string|DateTimeZone $toTimezone
     * @param string|DateTimeZone|null $fromTimezone (optional) - if not provided, will detect which to use.
     * @return DateTime|false
     */
    public function toTimezoneFromTimezone(string|DateTimeZone $toTimezone, string|DateTimeZone|null $fromTimezone = null): DateTime|bool
    {
        if ($toTimezone instanceof DateTimeZone) {
            $toTimezone = $toTimezone->getName();
        } else {
            if (TzConstants::isValidTimezone($toTimezone) === false) {
                return false;
            }
        }
        if ($fromTimezone instanceof DateTimeZone) {
            $fromTimezone = $fromTimezone->getName();
        }
        try {
            $dt = clone $this->dateTime;
            if ($fromTimezone === null) {
                $newDt = Date::now()->inTimezone($dt->getTimezone()->getName());
            } else {
                $newDt = Date::now()->inTimezone($fromTimezone);
            }
            Date::modify($newDt, false)->setDate(
                intval($dt->format('j')),
                intval($dt->format('n')),
                intval($dt->format('Y'))
            );
            Date::modify($newDt, false)->setTime(
                intval($dt->format('G')),
                intval($dt->format('i')),
                intval($dt->format('s')),
                intval($dt->format('u'))
            );

            $inst = new Timezone($newDt);
            return $inst->toTimezone(
                $toTimezone
            );
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
