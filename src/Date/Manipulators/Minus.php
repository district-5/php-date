<?php

namespace District5\Date\Manipulators;

use DateInterval;
use DateTime;
use Exception;

/**
 * Class Minus
 * @package District5\Date\Manipulators
 */
class Minus extends AbstractManipulator
{
    /**
     * Subtract $x hours from a DateTime.
     *
     * @param int $x
     * @return DateTime|false
     */
    public function hours(int $x)
    {
        return $this->run(
            sprintf('PT%sH', abs($x))
        );
    }

    /**
     * Subtract $x minutes from a DateTime.
     *
     * @param int $x
     * @return DateTime|false
     */
    public function minutes(int $x)
    {
        return $this->run(
            sprintf('PT%sM', abs($x))
        );
    }

    /**
     * Subtract $x seconds from a DateTime.
     *
     * @param int $x
     * @return DateTime|false
     */
    public function seconds(int $x)
    {
        return $this->run(
            sprintf('PT%sS', abs($x))
        );
    }

    /**
     * Subtract $x milliseconds from a DateTime.
     *
     * @param int $x
     * @return DateTime|false
     */
    public function milliseconds(int $x)
    {
        $this->dateTime->modify(
            sprintf('-%s milliseconds', abs($x))
        );
        return $this->dateTime;
    }

    /**
     * Subtract $x microseconds from a DateTime.
     *
     * @param int $x
     * @return DateTime|false
     */
    public function microseconds(int $x)
    {
        $this->dateTime->modify(
            sprintf('-%s microseconds', abs($x))
        );
        return $this->dateTime;
    }

    /**
     * Subtract hours and minutes from a DateTime.
     *
     * @param int $hours
     * @param int $minutes
     * @return DateTime|false
     */
    public function hoursAndMinutes(int $hours, int $minutes)
    {
        if (false === $this->hours($hours) || false === $this->minutes($minutes)) {
            return false;
        }
        return $this->dateTime;
    }

    /**
     * Subtract hours, minutes and seconds from a DateTime
     *
     * @param int $hours
     * @param int $minutes
     * @param int $seconds
     * @return DateTime|false
     */
    public function hoursAndMinutesAndSeconds(int $hours, int $minutes, int $seconds)
    {
        if (false === $this->hours($hours) || false === $this->minutes($minutes) || false === $this->seconds($seconds)) {
            return false;
        }
        return $this->dateTime;
    }

    /**
     * Subtract $x days from a DateTime
     *
     * @param int $x
     * @return DateTime|false
     */
    public function days(int $x)
    {
        return $this->run(
            sprintf('P%sD', abs($x))
        );
    }

    /**
     * Subtract $x weeks from a DateTime
     *
     * @param int $x
     * @return DateTime|false
     */
    public function weeks(int $x)
    {
        return $this->days(
            (7 * $x)
        );
    }

    /**
     * Subtract $x months from a DateTime
     *
     * @param int $x
     * @return DateTime|false
     */
    public function months(int $x)
    {
        $startDay = intval($this->dateTime->format('j'));
        $this->dateTime->modify(
            sprintf('-%s month', abs($x))
        );
        $endDay = intval($this->dateTime->format('j'));

        if ($startDay !== $endDay) {
            $this->dateTime->modify('last day of last month');
        }

        return $this->dateTime;
    }

    /**
     * Subtract $x years from a DateTime
     *
     * @param int $x
     * @return DateTime|false
     */
    public function years(int $x)
    {
        return $this->run(
            sprintf('P%sY', abs($x))
        );
    }

    /**
     * Subtract $x decades from a DateTime
     *
     * @param int $x
     * @return DateTime|false
     */
    public function decades(int $x)
    {
        return $this->years((abs($x) * 10));
    }

    /**
     * Subtract $x centuries from a DateTime
     *
     * @param int $x
     * @return DateTime|false
     */
    public function centuries(int $x)
    {
        return $this->years((abs($x) * 100));
    }

    /**
     * Subtract $x millennia from a DateTime
     *
     * @param int $x
     * @return DateTime|false
     */
    public function millennia(int $x)
    {
        return $this->years((abs($x) * 1000));
    }

    /**
     * Subtract from a DateTime using a DateInterval of $str.
     *
     * @param string $str
     * @return DateTime|false
     */
    protected function run(string $str)
    {
        try {
            $this->dateTime->sub(
                new DateInterval($str)
            );
            return $this->dateTime;
        } catch (Exception $e) {
        }
        return false;
    }
}
