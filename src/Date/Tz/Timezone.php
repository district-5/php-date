<?php

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
    public function toTimezone(string $timezone)
    {
        if (TzConstants::isValidTimezone($timezone) === false) {
            return false;
        }
        try {
            $dt = clone $this->dateTime;
            $dt->setTimezone(new DateTimeZone($timezone));
            return $dt;
        } catch (Exception $e) {
        }
        return false;
    }

    /**
     * Convert a datetime to a timezone from another timezone.
     *
     * @param string $toTimezone
     * @param string|null $fromTimezone (optional) - if not provided, will detect which to use.
     * @return DateTime|false
     */
    public function toTimezoneFromTimezone(string $toTimezone, string $fromTimezone = null)
    {
        if (TzConstants::isValidTimezone($toTimezone) === false) {
            return false;
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
        } catch (Exception $e) {
        }
        return false;
    }

    /**
     * @return DateTimeZone
     */
    public function getDefaultTimezone(): DateTimeZone
    {
        return new DateTimeZone(date_default_timezone_get());
    }
}
