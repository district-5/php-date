<?php

namespace District5\Date\Manipulators;

use DateTime;
use Exception;

/**
 * Class Modify
 * @package District5\Date\Manipulators
 * @method ddd
 */
class Modify extends AbstractManipulator
{
    /**
     * Apply an English string to a DateTime. Internally this uses `strtotime` and coerces
     * the value back to a DateTime object.
     *
     * @param string $content
     * @return DateTime|false
     * @see DateTime::modify()
     * @see https://www.php.net/manual/en/datetime.formats.relative.php
     */
    public function withString(string $content)
    {
        return $this->run(
            $content
        );
    }

    /**
     * @return Plus
     */
    public function plus(): Plus
    {
        return new Plus(
            $this->dateTime,
            $this->cloneDateTime
        );
    }

    /**
     * @return Minus
     */
    public function minus(): Minus
    {
        return new Minus(
            $this->dateTime,
            $this->cloneDateTime
        );
    }

    /**
     * Alias for plus
     *
     * @return Plus
     * @see Modify::plus()
     */
    public function add(): Plus
    {
        return $this->plus();
    }

    /**
     * Alias for minus
     *
     * @return Minus
     * @see Modify::minus()
     */
    public function subtract(): Minus
    {
        return $this->minus();
    }

    /**
     * Set the hour of the time on the datetime.
     *
     * @param int $hour
     * @return DateTime|false
     */
    public function setHours(int $hour)
    {
        if ($this->cloneDateTime === false) {
            $this->dateTime->setTime(
                $hour,
                intval($this->dateTime->format('i')),
                intval($this->dateTime->format('s')),
                intval($this->dateTime->format('u'))
            );
            return $this->dateTime;
        }
        $dt = clone $this->dateTime;
        $newString = $dt->format('Y-m-d');
        $newString .= ' ' . str_pad(strval($hour), 2, '0', STR_PAD_LEFT);
        $newString .= ':' . str_pad($this->dateTime->format('i'), 2, '0', STR_PAD_LEFT);
        $newString .= ':' . str_pad($this->dateTime->format('s'), 2, '0', STR_PAD_LEFT);
        $newString .= '.' . str_pad($this->dateTime->format('u'), 6, '0', STR_PAD_LEFT);
        return DateTime::createFromFormat(
            'Y-m-d H:i:s.u',
            $newString
        );
    }

    /**
     * Set the minutes of the time on the datetime.
     *
     * @param int $minutes
     * @return DateTime|false
     */
    public function setMinutes(int $minutes)
    {
        if ($this->cloneDateTime === false) {
            $this->dateTime->setTime(
                intval($this->dateTime->format('H')),
                $minutes,
                intval($this->dateTime->format('s')),
                intval($this->dateTime->format('u'))
            );
            return $this->dateTime;
        }
        $dt = clone $this->dateTime;
        $newString = $dt->format('Y-m-d');
        $newString .= ' ' . str_pad($this->dateTime->format('H'), 2, '0', STR_PAD_LEFT);
        $newString .= ':' . str_pad(strval($minutes), 2, '0', STR_PAD_LEFT);
        $newString .= ':' . str_pad($this->dateTime->format('s'), 2, '0', STR_PAD_LEFT);
        $newString .= '.' . str_pad($this->dateTime->format('u'), 6, '0', STR_PAD_LEFT);
        return DateTime::createFromFormat(
            'Y-m-d H:i:s.u',
            $newString
        );
    }

    /**
     * Set the seconds of the time on the datetime.
     *
     * @param int $seconds
     * @return DateTime|false
     */
    public function setSeconds(int $seconds)
    {
        if ($this->cloneDateTime === false) {
            $this->dateTime->setTime(
                intval($this->dateTime->format('H')),
                intval($this->dateTime->format('i')),
                $seconds,
                intval($this->dateTime->format('u'))
            );
            return $this->dateTime;
        }
        $dt = clone $this->dateTime;
        $newString = $dt->format('Y-m-d');
        $newString .= ' ' . str_pad($this->dateTime->format('H'), 2, '0', STR_PAD_LEFT);
        $newString .= ':' . str_pad($this->dateTime->format('i'), 2, '0', STR_PAD_LEFT);
        $newString .= ':' . str_pad(strval($seconds), 2, '0', STR_PAD_LEFT);
        $newString .= '.' . str_pad($this->dateTime->format('u'), 6, '0', STR_PAD_LEFT);
        return DateTime::createFromFormat(
            'Y-m-d H:i:s.u',
            $newString
        );
    }

    /**
     * Set the microseconds of the time on the datetime.
     *
     * @param int $microseconds
     * @return DateTime|false
     */
    public function setMicroseconds(int $microseconds)
    {
        if ($this->cloneDateTime === false) {
            $this->dateTime->setTime(
                intval($this->dateTime->format('H')),
                intval($this->dateTime->format('i')),
                intval($this->dateTime->format('s')),
                $microseconds
            );
            return $this->dateTime;
        }
        $dt = clone $this->dateTime;
        $newString = $dt->format('Y-m-d');
        $newString .= ' ' . str_pad($this->dateTime->format('H'), 2, '0', STR_PAD_LEFT);
        $newString .= ':' . str_pad($this->dateTime->format('i'), 2, '0', STR_PAD_LEFT);
        $newString .= ':' . str_pad($this->dateTime->format('s'), 2, '0', STR_PAD_LEFT);
        $newString .= '.' . str_pad(strval($microseconds), 6, '0', STR_PAD_LEFT);
        return DateTime::createFromFormat(
            'Y-m-d H:i:s.u',
            $newString
        );
    }

    /**
     * Set the time on the datetime.
     *
     * @param int $hour
     * @param int $minutes
     * @param int $seconds
     * @param int $microseconds
     * @return DateTime|false
     */
    public function setTime(int $hour, int $minutes, int $seconds = 0, int $microseconds = 0)
    {
        if ($this->cloneDateTime === false) {
            $this->dateTime->setTime($hour, $minutes, $seconds, $microseconds);
            return $this->dateTime;
        }
        $dt = clone $this->dateTime;
        $newString = $dt->format('Y-m-d');
        $newString .= ' ' . str_pad(strval($hour), 2, '0', STR_PAD_LEFT);
        $newString .= ':' . str_pad(strval($minutes), 2, '0', STR_PAD_LEFT);
        $newString .= ':' . str_pad(strval($seconds), 2, '0', STR_PAD_LEFT);
        $newString .= '.' . str_pad(strval($microseconds), 6, '0', STR_PAD_LEFT);
        return DateTime::createFromFormat(
            'Y-m-d H:i:s.u',
            $newString
        );
    }

    /**
     * Set the time on the datetime.
     *
     * @param int|null $day
     * @param int|null $month
     * @param int|null $year
     * @return DateTime|false
     */
    public function setDate(int $day = null, int $month = null, int $year = null)
    {
        if ($day === null) {
            $day = intval($this->dateTime->format('j'));
        }
        if ($month === null) {
            $month = intval($this->dateTime->format('n'));
        }
        if ($year === null) {
            $year = intval($this->dateTime->format('Y'));
        }
        if ($this->cloneDateTime === false) {
            $dt = $this->dateTime;
        } else {
            $dt = clone $this->dateTime;
        }
        $dt->setDate($year, $month, $day);
        if (intval($dt->format('Y')) !== $year || intval($dt->format('n')) !== $month || intval($dt->format('j')) !== $day) {
            return false;
        }
        return $dt;
    }

    /**
     * Apply the string to the DateTime object.
     *
     * @param string $str
     * @return DateTime|false
     */
    protected function run(string $str)
    {
        try {
            $dt = $this->dateTime;
            if ($this->cloneDateTime === true) {
                $dt = clone $this->dateTime;
            }
            return $dt->modify(
                $str
            );
        } catch (Exception $e) {
        }
        return false;
    }
}
