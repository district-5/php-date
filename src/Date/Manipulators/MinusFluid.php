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

use District5\Date\Date;

/**
 * Class MinusFluid
 * @package District5\Date\Manipulators
 */
class MinusFluid extends AbstractFluid
{
    /**
     * Add $x hours to a DateTime.
     *
     * @param int $x
     * @return MinusFluid
     */
    public function hours(int $x): MinusFluid
    {
        $this->dateTime = Date::modify($this->dateTime)->minus()->hours($x);
        return $this;
    }

    /**
     * Add $x minutes to a DateTime.
     *
     * @param int $x
     * @return MinusFluid
     */
    public function minutes(int $x): MinusFluid
    {
        $this->dateTime = Date::modify($this->dateTime)->minus()->minutes($x);
        return $this;
    }

    /**
     * Add $x seconds to a DateTime.
     *
     * @param int $x
     * @return MinusFluid
     */
    public function seconds(int $x): MinusFluid
    {
        $this->dateTime = Date::modify($this->dateTime)->minus()->seconds($x);
        return $this;
    }

    /**
     * Add $x milliseconds to a DateTime.
     *
     * @param int $x
     * @return MinusFluid
     */
    public function milliseconds(int $x): MinusFluid
    {
        $this->dateTime = Date::modify($this->dateTime)->minus()->milliseconds($x);
        return $this;
    }

    /**
     * Add $x microseconds to a DateTime.
     *
     * @param int $x
     * @return MinusFluid
     */
    public function microseconds(int $x): MinusFluid
    {
        $this->dateTime = Date::modify($this->dateTime)->minus()->microseconds($x);
        return $this;
    }

    /**
     * Add hours and minutes to a DateTime
     *
     * @param int $hours
     * @param int $minutes
     * @return MinusFluid
     */
    public function hoursAndMinutes(int $hours, int $minutes): MinusFluid
    {
        $this->dateTime = Date::modify($this->dateTime)->minus()->hoursAndMinutes($hours, $minutes);
        return $this;
    }

    /**
     * Add hours, minutes and seconds to a DateTime
     *
     * @param int $hours
     * @param int $minutes
     * @param int $seconds
     * @return MinusFluid
     */
    public function hoursAndMinutesAndSeconds(int $hours, int $minutes, int $seconds): MinusFluid
    {
        $this->dateTime = Date::modify($this->dateTime)->minus()->hoursAndMinutesAndSeconds($hours, $minutes, $seconds);
        return $this;
    }

    /**
     * Add $x days to a DateTime.
     *
     * @param int $x
     * @return MinusFluid
     */
    public function days(int $x): MinusFluid
    {
        $this->dateTime = Date::modify($this->dateTime)->minus()->days($x);
        return $this;
    }

    /**
     * Add $x weeks to a DateTime
     *
     * @param int $x
     * @return MinusFluid
     */
    public function weeks(int $x): MinusFluid
    {
        $this->dateTime = Date::modify($this->dateTime)->minus()->weeks($x);
        return $this;
    }

    /**
     * Add $x months to a DateTime
     *
     * @param int $x
     * @return MinusFluid
     */
    public function months(int $x): MinusFluid
    {
        $this->dateTime = Date::modify($this->dateTime)->minus()->months($x);
        return $this;
    }

    /**
     * Add $x years to a DateTime.
     *
     * @param int $x
     * @return MinusFluid
     */
    public function years(int $x): MinusFluid
    {
        $this->dateTime = Date::modify($this->dateTime)->minus()->years($x);
        return $this;
    }

    /**
     * Add $x decades to a DateTime.
     *
     * @param int $x
     * @return MinusFluid
     */
    public function decades(int $x): MinusFluid
    {
        $this->dateTime = Date::modify($this->dateTime)->minus()->decades($x);
        return $this;
    }

    /**
     * Add $x centuries to a DateTime.
     *
     * @param int $x
     * @return MinusFluid
     */
    public function centuries(int $x): MinusFluid
    {
        $this->dateTime = Date::modify($this->dateTime)->minus()->centuries($x);
        return $this;
    }

    /**
     * Add $x millennia to a DateTime
     *
     * @param int $x
     * @return MinusFluid
     */
    public function millennia(int $x): MinusFluid
    {
        $this->dateTime = Date::modify($this->dateTime)->minus()->millennia($x);
        return $this;
    }
}
