<?php

namespace District5\Date\Tz;

use DateTime;
use DateTimeZone;
use Exception;

/**
 * Class NowTimezone
 * @package District5\Date\Tz
 */
class NowTimezone
{
    /**
     * @param string $offset
     * @return DateTime|false
     */
    public function fromOffset(string $offset)
    {
        if (in_array($offset, ['0', '00', '0000', '00:00', '+0', '+00', '+0000', '+00:00'])) {
            try {
                return new DateTime(
                    'now',
                    new DateTimeZone('+0')
                );
            } catch (Exception $e) {
            }
            return false;
        }
        $len = strlen($offset);
        if ($len < 4 || $len > 5) {
            // Format is +100 or +0100
            return false;
        }

        $dir = substr($offset, 0, 1);
        if (!in_array($dir, ['+', '-'])) {
            // Offsets are required to start with + or -
            return false;
        }

        if ($len === 4) {
            $offset = sprintf('%s0%s', $dir, substr($offset, 1));
        }
        try {
            return new DateTime(
                'now',
                new DateTimeZone($offset)
            );
        } catch (Exception $e) {
        }
        return false;
    }

    /**
     * @param string $timezone
     * @return DateTime|false
     * @see TzConstants
     */
    public function inTimezone(string $timezone)
    {
        if (TzConstants::isValidTimezone($timezone) === false) {
            return false;
        }
        try {
            return new DateTime(
                'now',
                new DateTimeZone($timezone)
            );
        } catch (Exception $e) {
        }
        return false;
    }

    /**
     * @return DateTime|false
     * @noinspection PhpUnused
     */
    public function london()
    {
        return $this->inTimezone(
            TzConstants::EUROPE_LONDON
        );
    }

    /**
     * @return DateTime|false
     * @noinspection PhpUnused
     */
    public function pacific()
    {
        return $this->inTimezone(
            TzConstants::AMERICA_LOS_ANGELES
        );
    }

    /**
     * @return DateTime|false
     * @noinspection PhpUnused
     */
    public function eastern()
    {
        return $this->inTimezone(
            TzConstants::AMERICA_NEW_YORK
        );
    }

    /**
     * @return DateTime|false
     * @noinspection PhpUnused
     */
    public function central()
    {
        return $this->inTimezone(
            TzConstants::AMERICA_CHICAGO
        );
    }

    /**
     * @return DateTime|false
     */
    public function utc()
    {
        try {
            return new DateTime(
                'now',
                new DateTimeZone(TzConstants::UTC)
            );
        } catch (Exception $e) {
        }
        return false;
    }

    /**
     * Get the current DateTime based off of the default timezone configuration in PHP.
     *
     * @return DateTime
     * @noinspection PhpUnhandledExceptionInspection
     * @noinspection PhpDocMissingThrowsInspection
     */
    public function default(): DateTime
    {
        return new DateTime(
            'now',
            new DateTimeZone(date_default_timezone_get())
        );
    }
}
