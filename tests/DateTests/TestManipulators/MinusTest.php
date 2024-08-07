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

namespace District5Tests\DateTests\TestManipulators;

use DateTime;
use District5\Date\Date;
use District5\Date\Manipulators\Minus;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

/**
 * Class MinusTest
 * @package District5Tests\DateTests\TestManipulators
 */
class MinusTest extends TestCase
{
    public function testMinusSingleSecond()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 00:15:10');
        $this->assertEquals(
            '2010-01-01 00:15:09',
            Date::modify($dt)->minus()->seconds(1)->format('Y-m-d H:i:s')
        );
    }

    public function testMinusSingleMillisecond()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s.v', '2010-01-01 00:15:10.999');
        $this->assertEquals(
            '2010-01-01 00:15:10.998',
            Date::modify($dt)->minus()->milliseconds(1)->format('Y-m-d H:i:s.v')
        );
    }

    public function testMinusSingleMicrosecond()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s.u', '2010-01-01 00:15:10.999000');
        $this->assertEquals(
            '2010-01-01 00:15:10.998999',
            Date::modify($dt)->minus()->microseconds(1)->format('Y-m-d H:i:s.u')
        );
    }

    public function testMinusTenMilliseconds()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s.v', '2010-01-01 00:15:10.999');
        $this->assertEquals(
            '2010-01-01 00:15:10.989',
            Date::modify($dt)->minus()->milliseconds(10)->format('Y-m-d H:i:s.v')
        );
    }

    public function testMinusTenMicroseconds()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s.u', '2010-01-01 00:15:10.999000');
        $this->assertEquals(
            '2010-01-01 00:15:10.998990',
            Date::modify($dt)->minus()->microseconds(10)->format('Y-m-d H:i:s.u')
        );
    }

    public function testMinusTwentySeconds()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 00:15:10');
        $this->assertEquals(
            '2010-01-01 00:14:50',
            Date::modify($dt)->minus()->seconds(20)->format('Y-m-d H:i:s')
        );
    }

    public function testMinusSingleMinute()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 00:15:10');
        $this->assertEquals(
            '2010-01-01 00:14:10',
            Date::modify($dt)->minus()->minutes(1)->format('Y-m-d H:i:s')
        );
    }

    public function testMinusTwentyMinutes()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 00:15:10');
        $this->assertEquals(
            '2009-12-31 23:55:10',
            Date::modify($dt)->minus()->minutes(20)->format('Y-m-d H:i:s')
        );
    }

    public function testMinusSingleHour()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 00:15:10');
        $this->assertEquals(
            '2009-12-31 23:15:10',
            Date::modify($dt)->minus()->hours(1)->format('Y-m-d H:i:s')
        );
    }

    public function testMinusSingleHourSingleMinute()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 00:15:10');
        $this->assertEquals(
            '2009-12-31 23:14:10',
            Date::modify($dt)->minus()->hoursAndMinutes(1, 1)->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            '2010-01-01 00:15:10',
            Date::modify($dt)->minus()->hoursAndMinutes(0, 0)->format('Y-m-d H:i:s')
        );
    }

    public function testMinusSingleHourSingleMinuteSingleSecond()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 00:15:10');
        $this->assertEquals(
            '2009-12-31 23:14:09',
            Date::modify($dt)->minus()->hoursAndMinutesAndSeconds(1, 1, 1)->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            '2010-01-01 00:15:10',
            Date::modify($dt)->minus()->hoursAndMinutesAndSeconds(0, 0, 0)->format('Y-m-d H:i:s')
        );
    }

    public function testMinusTwentyHours()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 09:00:00');
        $this->assertEquals(
            '2009-12-31 13:00:00',
            Date::modify($dt)->minus()->hours(20)->format('Y-m-d H:i:s')
        );
    }

    public function testMinusSingleMonth()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-01-16');
        $this->assertEquals(
            '2018-12-16',
            Date::modify($dt)->minus()->months(1)->format('Y-m-d')
        );
    }

    public function testMinusTrickyMonth()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-03-29');
        $this->assertEquals(
            '2019-02-28',
            Date::modify($dt)->minus()->months(1)->format('Y-m-d')
        );

        $dt = DateTime::createFromFormat('Y-m-d', '2020-03-29');
        $this->assertEquals(
            '2020-02-29',
            Date::modify($dt)->minus()->months(1)->format('Y-m-d')
        );
    }

    public function testMinusTwelveMonths()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-01-16');
        $this->assertEquals(
            '2018-01-16',
            Date::modify($dt)->minus()->months(12)->format('Y-m-d')
        );
    }

    public function testMinusSingleDay()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-01-16');
        $this->assertEquals(
            '2019-01-15',
            Date::modify($dt)->minus()->days(1)->format('Y-m-d')
        );
    }

    public function testMinusSingleWeek()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-01-16');
        $this->assertEquals(
            '2019-01-09',
            Date::modify($dt)->minus()->weeks(1)->format('Y-m-d')
        );
    }

    public function testMinusTwentyDays()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-09-30');
        $this->assertEquals(
            '2019-09-10',
            Date::modify($dt)->minus()->days(20)->format('Y-m-d')
        );
    }

    public function testMinusTwoWeeks()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-09-30');
        $this->assertEquals(
            '2019-09-16',
            Date::modify($dt)->minus()->weeks(2)->format('Y-m-d')
        );
    }

    public function testMinusSingleYear()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-09-30');
        $this->assertEquals(
            '2018-09-30',
            Date::modify($dt)->minus()->years(1)->format('Y-m-d')
        );
    }

    public function testMinusFiftyYears()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2049-01-01');
        $this->assertEquals(
            '1999-01-01',
            Date::modify($dt)->minus()->years(50)->format('Y-m-d')
        );
    }

    public function testMinusSingleDecade()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-09-30');
        $this->assertEquals(
            '2009-09-30',
            Date::modify($dt)->minus()->decades(1)->format('Y-m-d')
        );
    }

    public function testMinusFourDecades()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-09-30');
        $this->assertEquals(
            '1979-09-30',
            Date::modify($dt)->minus()->decades(4)->format('Y-m-d')
        );
    }

    public function testMinusOneMillennia()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-09-30');
        $this->assertEquals(
            '1019-09-30',
            Date::modify($dt)->minus()->millennia(1)->format('Y-m-d')
        );
    }

    public function testMinusFourMillennia()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-09-30');
        $this->assertEquals(
            '-1981-09-30',
            Date::modify($dt)->minus()->millennia(4)->format('Y-m-d')
        );
    }

    public function testMinusSingleCentury()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-09-30');
        $this->assertEquals(
            '1919-09-30',
            Date::modify($dt)->minus()->centuries(1)->format('Y-m-d')
        );
    }

    public function testMinusFourCenturies()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-09-30');
        $this->assertEquals(
            '1619-09-30',
            Date::modify($dt)->minus()->centuries(4)->format('Y-m-d')
        );
    }

    public function testMinusSingleSecondInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 00:15:10');
        Date::modify($dt, false)->minus()->seconds(1);
        $this->assertEquals(
            '2010-01-01 00:15:09',
            $dt->format('Y-m-d H:i:s')
        );
    }

    public function testMinusTwentySecondsInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 00:15:10');
        Date::modify($dt, false)->minus()->seconds(20);
        $this->assertEquals(
            '2010-01-01 00:14:50',
            $dt->format('Y-m-d H:i:s')
        );
    }

    public function testMinusSingleMinuteInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 00:15:10');
        Date::modify($dt, false)->minus()->minutes(1);
        $this->assertEquals(
            '2010-01-01 00:14:10',
            $dt->format('Y-m-d H:i:s')
        );
    }

    public function testMinusTwentyMinutesInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 00:15:10');
        Date::modify($dt, false)->minus()->minutes(20);
        $this->assertEquals(
            '2009-12-31 23:55:10',
            $dt->format('Y-m-d H:i:s')
        );
    }

    public function testMinusSingleHourInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 00:15:10');
        Date::modify($dt, false)->minus()->hours(1);
        $this->assertEquals(
            '2009-12-31 23:15:10',
            $dt->format('Y-m-d H:i:s')
        );
    }

    public function testMinusSingleHourSingleMinuteInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 00:15:10');
        Date::modify($dt, false)->minus()->hoursAndMinutes(1, 1);
        $this->assertEquals(
            '2009-12-31 23:14:10',
            $dt->format('Y-m-d H:i:s')
        );

        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 00:15:10');
        Date::modify($dt, false)->minus()->hoursAndMinutes(0, 0);
        $this->assertEquals(
            '2010-01-01 00:15:10',
            $dt->format('Y-m-d H:i:s')
        );
    }

    public function testMinusSingleHourSingleMinuteSingleSecondInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 00:15:10');
        Date::modify($dt, false)->minus()->hoursAndMinutesAndSeconds(1, 1, 1);
        $this->assertEquals(
            '2009-12-31 23:14:09',
            $dt->format('Y-m-d H:i:s')
        );

        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 00:15:10');
        Date::modify($dt, false)->minus()->hoursAndMinutesAndSeconds(0, 0, 0);
        $this->assertEquals(
            '2010-01-01 00:15:10',
            $dt->format('Y-m-d H:i:s')
        );
    }

    public function testMinusTwentyHoursInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 09:00:00');
        Date::modify($dt, false)->minus()->hours(20);
        $this->assertEquals(
            '2009-12-31 13:00:00',
            $dt->format('Y-m-d H:i:s')
        );
    }

    public function testMinusSingleMonthInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-01-16');
        Date::modify($dt, false)->minus()->months(1);
        $this->assertEquals(
            '2018-12-16',
            $dt->format('Y-m-d')
        );
    }

    public function testMinusTrickyMonthInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-03-29');
        Date::modify($dt, false)->minus()->months(1);
        $this->assertEquals(
            '2019-02-28',
            $dt->format('Y-m-d')
        );

        $dt = DateTime::createFromFormat('Y-m-d', '2020-03-29');
        Date::modify($dt, false)->minus()->months(1);
        $this->assertEquals(
            '2020-02-29',
            $dt->format('Y-m-d')
        );
    }

    public function testMinusTwelveMonthsInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-01-16');
        Date::modify($dt, false)->minus()->months(12);
        $this->assertEquals(
            '2018-01-16',
            $dt->format('Y-m-d')
        );
    }

    public function testMinusSingleDayInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-01-16');
        Date::modify($dt, false)->minus()->days(1);
        $this->assertEquals(
            '2019-01-15',
            $dt->format('Y-m-d')
        );
    }

    public function testMinusSingleWeekInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-01-16');
        Date::modify($dt, false)->minus()->weeks(1);
        $this->assertEquals(
            '2019-01-09',
            $dt->format('Y-m-d')
        );
    }

    public function testMinusTwentyDaysInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-09-30');
        Date::modify($dt, false)->minus()->days(20);
        $this->assertEquals(
            '2019-09-10',
            $dt->format('Y-m-d')
        );
    }

    public function testMinusTwoWeeksInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-09-30');
        Date::modify($dt, false)->minus()->weeks(2);
        $this->assertEquals(
            '2019-09-16',
            $dt->format('Y-m-d')
        );
    }

    public function testMinusSingleYearInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-09-30');
        Date::modify($dt, false)->minus()->years(1);
        $this->assertEquals(
            '2018-09-30',
            $dt->format('Y-m-d')
        );
    }

    public function testMinusFiftyYearsInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2049-01-01');
        Date::modify($dt, false)->minus()->years(50);
        $this->assertEquals(
            '1999-01-01',
            $dt->format('Y-m-d')
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testRunWithInvalidString()
    {
        $class = new ReflectionClass(Minus::class);
        $method = $class->getMethod('run');
        $method->setAccessible(true);

        $date = DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 00:15:10');
        $minus = new Minus($date);
        $result = $method->invokeArgs($minus, ['run', 'this isn\'t valid']);
        $this->assertFalse($result);
    }
}
