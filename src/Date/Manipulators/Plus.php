<?php

namespace District5\Date\Manipulators;

use DateInterval;
use DateTime;
use Exception;

/**
 * Class Plus
 * @package District5\Date\Manipulators
 */
class Plus extends AbstractManipulator
{
    /**
     * Add $x hours to a DateTime.
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
     * Add $x minutes to a DateTime.
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
     * Add $x seconds to a DateTime.
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
     * Add $x milliseconds to a DateTime.
     *
     * @param int $x
     * @return DateTime|false
     */
    public function milliseconds(int $x)
    {
        $this->dateTime->modify(
            sprintf('+%s milliseconds', abs($x))
        );
        return $this->dateTime;
    }

    /**
     * Add $x microseconds to a DateTime.
     *
     * @param int $x
     * @return DateTime|false
     */
    public function microseconds(int $x)
    {
        $this->dateTime->modify(
            sprintf('+%s microseconds', abs($x))
        );
        return $this->dateTime;
    }

    /**
     * Add hours and minutes to a DateTime
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
     * Add hours, minutes and seconds to a DateTime
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
     * Add $x days to a DateTime.
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
     * Add $x weeks to a DateTime
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
     * Add $x months to a DateTime
     *
     * @param int $x
     * @return DateTime|false
     */
    public function months(int $x)
    {
        $startDay = intval($this->dateTime->format('j'));
        $this->dateTime->modify(
            sprintf('+%s month', abs($x))
        );
        $endDay = intval($this->dateTime->format('j'));

        if ($startDay !== $endDay) {
            $this->dateTime->modify('last day of last month');
        }

        return $this->dateTime;
    }

    /**
     * Add $x years to a DateTime.
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
     * Add $x decades to a DateTime.
     *
     * @param int $x
     * @return DateTime|false
     */
    public function decades(int $x)
    {
        return $this->years((abs($x) * 10));
    }

    /**
     * Add $x centuries to a DateTime.
     *
     * @param int $x
     * @return DateTime|false
     */
    public function centuries(int $x)
    {
        return $this->years((abs($x) * 100));
    }

    /**
     * Add $x millennia to a DateTime
     *
     * @param int $x
     * @return DateTime|false
     */
    public function millennia(int $x)
    {
        return $this->years((abs($x) * 1000));
    }

    /**
     * Add to a DateTime using a DateInterval of $str.
     *
     * @param string $str
     * @return DateTime|false
     */
    protected function run(string $str)
    {
        try {
            $this->dateTime->add(
                new DateInterval($str)
            );
            return $this->dateTime;
        } catch (Exception $e) {
        }
        return false;
    }
}
