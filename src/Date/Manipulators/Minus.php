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
    public function hours(int $x): DateTime|bool
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
    public function minutes(int $x): DateTime|bool
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
    public function seconds(int $x): DateTime|bool
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
    public function milliseconds(int $x): DateTime|bool
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
    public function microseconds(int $x): DateTime|bool
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
    public function hoursAndMinutes(int $hours, int $minutes): DateTime|bool
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
    public function hoursAndMinutesAndSeconds(int $hours, int $minutes, int $seconds): DateTime|bool
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
    public function days(int $x): DateTime|bool
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
    public function weeks(int $x): DateTime|bool
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
    public function months(int $x): DateTime|bool
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
    public function years(int $x): DateTime|bool
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
    public function decades(int $x): DateTime|bool
    {
        return $this->years((abs($x) * 10));
    }

    /**
     * Subtract $x centuries from a DateTime
     *
     * @param int $x
     * @return DateTime|false
     */
    public function centuries(int $x): DateTime|bool
    {
        return $this->years((abs($x) * 100));
    }

    /**
     * Subtract $x millennia from a DateTime
     *
     * @param int $x
     * @return DateTime|false
     */
    public function millennia(int $x): DateTime|bool
    {
        return $this->years((abs($x) * 1000));
    }

    /**
     * Subtract from a DateTime using a DateInterval of $str.
     *
     * @param string $str
     * @return DateTime|false
     */
    protected function run(string $str): DateTime|bool
    {
        try {
            $this->dateTime->sub(
                new DateInterval($str)
            );
            return $this->dateTime;
        } catch (Exception) {
        }
        return false;
    }
}
