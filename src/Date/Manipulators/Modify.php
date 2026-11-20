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

namespace District5\Date\Manipulators;

use DateTime;
use Exception;

/**
 * Class Modify
 * @package District5\Date\Manipulators
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
    public function withString(string $content): DateTime|bool
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
     * @return PlusFluid
     */
    public function plusFluid(): PlusFluid
    {
        return new PlusFluid(
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
     * @return MinusFluid
     */
    public function minusFluid(): MinusFluid
    {
        return new MinusFluid(
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
     * Alias for plusFluid
     *
     * @return PlusFluid
     * @see Modify::plus()
     */
    public function addFluid(): PlusFluid
    {
        return $this->plusFluid();
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
     * Alias for minusFluid
     *
     * @return MinusFluid
     * @see Modify::minus()
     */
    public function subtractFluid(): MinusFluid
    {
        return $this->minusFluid();
    }

    /**
     * Alias for minus
     *
     * @return Minus
     * @see Modify::minus()
     */
    public function sub(): Minus
    {
        return $this->minus();
    }

    /**
     * Alias for minusFluid
     *
     * @return MinusFluid
     * @see Modify::minus()
     */
    public function subFluid(): MinusFluid
    {
        return $this->minusFluid();
    }

    /**
     * Set the hour of the time on the datetime.
     *
     * @param int $hour
     * @return DateTime|false
     */
    public function setHours(int $hour): DateTime|bool
    {
        return $this->applyTime(
            $hour,
            intval($this->dateTime->format('i')),
            intval($this->dateTime->format('s')),
            intval($this->dateTime->format('u'))
        );
    }

    /**
     * Set the minutes of the time on the datetime.
     *
     * @param int $minutes
     * @return DateTime|false
     */
    public function setMinutes(int $minutes): DateTime|bool
    {
        return $this->applyTime(
            intval($this->dateTime->format('H')),
            $minutes,
            intval($this->dateTime->format('s')),
            intval($this->dateTime->format('u'))
        );
    }

    /**
     * Set the seconds of the time on the datetime.
     *
     * @param int $seconds
     * @return DateTime|false
     */
    public function setSeconds(int $seconds): DateTime|bool
    {
        return $this->applyTime(
            intval($this->dateTime->format('H')),
            intval($this->dateTime->format('i')),
            $seconds,
            intval($this->dateTime->format('u'))
        );
    }

    /**
     * Set the microseconds of the time on the datetime.
     *
     * @param int $microseconds
     * @return DateTime|false
     */
    public function setMicroseconds(int $microseconds): DateTime|bool
    {
        return $this->applyTime(
            intval($this->dateTime->format('H')),
            intval($this->dateTime->format('i')),
            intval($this->dateTime->format('s')),
            $microseconds
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
    public function setTime(int $hour, int $minutes, int $seconds = 0, int $microseconds = 0): DateTime|bool
    {
        return $this->applyTime($hour, $minutes, $seconds, $microseconds);
    }

    /**
     * Set the time on the datetime.
     *
     * @param int|null $day
     * @param int|null $month
     * @param int|null $year
     * @return DateTime|false
     */
    public function setDate(?int $day = null, ?int $month = null, ?int $year = null): DateTime|bool
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
        // Validate against a copy first, so an invalid date never alters the original in place.
        $dt = clone $this->dateTime;
        $dt->setDate($year, $month, $day);
        if (intval($dt->format('Y')) !== $year || intval($dt->format('n')) !== $month || intval($dt->format('j')) !== $day) {
            return false;
        }
        if ($this->cloneDateTime === false) {
            $this->dateTime->setDate($year, $month, $day);
            return $this->dateTime;
        }
        return $dt;
    }

    /**
     * Validate and apply a time to the DateTime (or a clone of it), preserving the timezone.
     * Out of range values return false and leave the DateTime untouched.
     *
     * @param int $hour 0-23
     * @param int $minutes 0-59
     * @param int $seconds 0-59
     * @param int $microseconds 0-999999
     * @return DateTime|false
     */
    private function applyTime(int $hour, int $minutes, int $seconds, int $microseconds): DateTime|bool
    {
        if ($hour < 0 || $hour > 23
            || $minutes < 0 || $minutes > 59
            || $seconds < 0 || $seconds > 59
            || $microseconds < 0 || $microseconds > 999999) {
            return false;
        }
        $dt = $this->cloneDateTime === true ? clone $this->dateTime : $this->dateTime;
        $dt->setTime($hour, $minutes, $seconds, $microseconds);
        return $dt;
    }

    /**
     * Apply the string to the DateTime object.
     *
     * @param string $str
     * @return DateTime|false
     */
    protected function run(string $str): DateTime|bool
    {
        try {
            $dt = $this->dateTime;
            if ($this->cloneDateTime === true) {
                $dt = clone $this->dateTime;
            }
            return $dt->modify(
                $str
            );
        } catch (Exception) {
        }
        return false;
    }
}
