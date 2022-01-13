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
use PHPUnit\Framework\TestCase;

/**
 * Class PlusTest
 * @package District5Tests\DateTests\TestManipulators
 */
class PlusTest extends TestCase
{
    public function testPlusSingleSecond()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 00:15:09');
        $this->assertEquals(
            '2010-01-01 00:15:10',
            Date::modify($dt)->plus()->seconds(1)->format('Y-m-d H:i:s')
        );
    }

    public function testPlusSingleMillisecond()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s.v', '2010-01-01 00:15:10.998');
        $this->assertEquals(
            '2010-01-01 00:15:10.999',
            Date::modify($dt)->plus()->milliseconds(1)->format('Y-m-d H:i:s.v')
        );
    }

    public function testPlusSingleMicrosecond()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s.u', '2010-01-01 00:15:10.999000');
        $this->assertEquals(
            '2010-01-01 00:15:10.999001',
            Date::modify($dt)->plus()->microseconds(1)->format('Y-m-d H:i:s.u')
        );
    }

    public function testPlusTenMilliseconds()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s.v', '2010-01-01 00:15:10.998');
        $this->assertEquals(
            '2010-01-01 00:15:11.008',
            Date::modify($dt)->plus()->milliseconds(10)->format('Y-m-d H:i:s.v')
        );
    }

    public function testPlusTenMicroseconds()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s.u', '2010-01-01 00:15:10.999000');
        $this->assertEquals(
            '2010-01-01 00:15:10.999010',
            Date::modify($dt)->plus()->microseconds(10)->format('Y-m-d H:i:s.u')
        );
    }

    public function testPlusTwentySeconds()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 00:14:50');
        $this->assertEquals(
            '2010-01-01 00:15:10',
            Date::modify($dt)->plus()->seconds(20)->format('Y-m-d H:i:s')
        );
    }

    public function testPlusSingleMinute()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 00:14:10');
        $this->assertEquals(
            '2010-01-01 00:15:10',
            Date::modify($dt)->plus()->minutes(1)->format('Y-m-d H:i:s')
        );
    }

    public function testPlusTwentyMinutes()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2009-12-31 23:55:10');
        $this->assertEquals(
            '2010-01-01 00:15:10',
            Date::modify($dt)->plus()->minutes(20)->format('Y-m-d H:i:s')
        );
    }

    public function testPlusSingleHour()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2009-12-31 23:15:10');
        $this->assertEquals(
            '2010-01-01 00:15:10',
            Date::modify($dt)->plus()->hours(1)->format('Y-m-d H:i:s')
        );
    }

    public function testPlusSingleHourSingleMinute()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2009-12-31 23:15:10');
        $this->assertEquals(
            '2010-01-01 00:16:10',
            Date::modify($dt)->plus()->hoursAndMinutes(1, 1)->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            '2009-12-31 23:15:10',
            Date::modify($dt)->plus()->hoursAndMinutes(0, 0)->format('Y-m-d H:i:s')
        );
    }

    public function testPlusSingleHourSingleMinuteSingleSecond()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2009-12-31 23:15:10');
        $this->assertEquals(
            '2010-01-01 00:16:11',
            Date::modify($dt)->plus()->hoursAndMinutesAndSeconds(1, 1, 1)->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            '2009-12-31 23:15:10',
            Date::modify($dt)->plus()->hoursAndMinutesAndSeconds(0, 0, 0)->format('Y-m-d H:i:s')
        );
    }

    public function testPlusTwentyHours()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2009-12-31 13:00:00');
        $this->assertEquals(
            '2010-01-01 09:00:00',
            Date::modify($dt)->plus()->hours(20)->format('Y-m-d H:i:s')
        );
    }

    public function testAddSingleDay()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-09-30');
        $this->assertEquals(
            '2019-10-01',
            Date::modify($dt)->plus()->days(1)->format('Y-m-d')
        );
    }

    public function testAddSingleWeek()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-09-30');
        $this->assertEquals(
            '2019-10-07',
            Date::modify($dt)->plus()->weeks(1)->format('Y-m-d')
        );
    }

    public function testAddTwentyDays()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-09-30');
        $this->assertEquals(
            '2019-10-20',
            Date::modify($dt)->plus()->days(20)->format('Y-m-d')
        );
    }

    public function testAddTwoWeeks()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-09-30');
        $this->assertEquals(
            '2019-10-14',
            Date::modify($dt)->plus()->weeks(2)->format('Y-m-d')
        );
    }

    public function testAddSingleMonth()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-09-30');
        $this->assertEquals(
            '2019-10-30',
            Date::modify($dt)->plus()->months(1)->format('Y-m-d')
        );
    }

    public function testAddTrickyMonth()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2020-01-29');
        $this->assertEquals(
            '2020-02-29',
            Date::modify($dt)->plus()->months(1)->format('Y-m-d')
        );

        $dt = DateTime::createFromFormat('Y-m-d', '2020-01-29');
        $this->assertEquals(
            '2020-02-29',
            Date::modify($dt)->plus()->months(1)->format('Y-m-d')
        );
    }

    public function testAddTwelveMonths()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-09-30');
        $this->assertEquals(
            '2020-09-30',
            Date::modify($dt)->plus()->months(12)->format('Y-m-d')
        );
    }

    public function testAddSingleYear()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-09-30');
        $this->assertEquals(
            '2020-09-30',
            Date::modify($dt)->plus()->years(1)->format('Y-m-d')
        );
    }

    public function testAddFiftyYears()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2049-01-01');
        $this->assertEquals(
            '2099-01-01',
            Date::modify($dt)->plus()->years(50)->format('Y-m-d')
        );
    }

    public function testAddSingleDecade()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-09-30');
        $this->assertEquals(
            '2029-09-30',
            Date::modify($dt)->plus()->decades(1)->format('Y-m-d')
        );
    }

    public function testAddFourDecades()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-09-30');
        $this->assertEquals(
            '2059-09-30',
            Date::modify($dt)->plus()->decades(4)->format('Y-m-d')
        );
    }

    public function testAddSingleCentury()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-09-30');
        $this->assertEquals(
            '2119-09-30',
            Date::modify($dt)->plus()->centuries(1)->format('Y-m-d')
        );
    }

    public function testAddFourCenturies()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-09-30');
        $this->assertEquals(
            '2419-09-30',
            Date::modify($dt)->plus()->centuries(4)->format('Y-m-d')
        );
    }

    public function testAddOneMillennia()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-09-30');
        $this->assertEquals(
            '3019-09-30',
            Date::modify($dt)->plus()->millennia(1)->format('Y-m-d')
        );
    }

    public function testAddFourMillennia()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-09-30');
        $this->assertEquals(
            '6019-09-30',
            Date::modify($dt)->plus()->millennia(4)->format('Y-m-d')
        );
    }

    public function testPlusSingleSecondInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 00:15:09');
        Date::modify($dt, false)->plus()->seconds(1);
        $this->assertEquals(
            '2010-01-01 00:15:10',
            $dt->format('Y-m-d H:i:s')
        );
    }

    public function testPlusTwentySecondsInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 00:14:50');
        Date::modify($dt, false)->plus()->seconds(20);
        $this->assertEquals(
            '2010-01-01 00:15:10',
            $dt->format('Y-m-d H:i:s')
        );
    }

    public function testPlusSingleMinuteInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 00:14:10');
        Date::modify($dt, false)->plus()->minutes(1);
        $this->assertEquals(
            '2010-01-01 00:15:10',
            $dt->format('Y-m-d H:i:s')
        );
    }

    public function testPlusTwentyMinutesInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2009-12-31 23:55:10');
        Date::modify($dt, false)->plus()->minutes(20);
        $this->assertEquals(
            '2010-01-01 00:15:10',
            $dt->format('Y-m-d H:i:s')
        );
    }

    public function testPlusSingleHourInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2009-12-31 23:15:10');
        Date::modify($dt, false)->plus()->hours(1);
        $this->assertEquals(
            '2010-01-01 00:15:10',
            $dt->format('Y-m-d H:i:s')
        );
    }

    public function testPlusSingleHourSingleMinuteInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2009-12-31 23:15:10');
        Date::modify($dt, false)->plus()->hoursAndMinutes(1, 1);
        $this->assertEquals(
            '2010-01-01 00:16:10',
            $dt->format('Y-m-d H:i:s')
        );

        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2009-12-31 23:15:10');
        Date::modify($dt, false)->plus()->hoursAndMinutes(0, 0);
        $this->assertEquals(
            '2009-12-31 23:15:10',
            $dt->format('Y-m-d H:i:s')
        );
    }

    public function testPlusSingleHourSingleMinuteSingleSecondInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2009-12-31 23:15:10');
        Date::modify($dt, false)->plus()->hoursAndMinutesAndSeconds(1, 1, 1);
        $this->assertEquals(
            '2010-01-01 00:16:11',
            $dt->format('Y-m-d H:i:s')
        );

        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2009-12-31 23:15:10');
        Date::modify($dt, false)->plus()->hoursAndMinutesAndSeconds(0, 0, 0);
        $this->assertEquals(
            '2009-12-31 23:15:10',
            $dt->format('Y-m-d H:i:s')
        );
    }

    public function testPlusTwentyHoursInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2009-12-31 13:00:00');
        Date::modify($dt, false)->plus()->hours(20);
        $this->assertEquals(
            '2010-01-01 09:00:00',
            $dt->format('Y-m-d H:i:s')
        );
    }

    public function testAddSingleDayInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-09-30');
        Date::modify($dt, false)->plus()->days(1);
        $this->assertEquals(
            '2019-10-01',
            $dt->format('Y-m-d')
        );
    }

    public function testAddSingleWeekInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-09-30');
        Date::modify($dt, false)->plus()->weeks(1);
        $this->assertEquals(
            '2019-10-07',
            $dt->format('Y-m-d')
        );
    }

    public function testAddTwentyDaysInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-09-30');
        Date::modify($dt, false)->plus()->days(20);
        $this->assertEquals(
            '2019-10-20',
            $dt->format('Y-m-d')
        );
    }

    public function testAddTwoWeeksInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-09-30');
        Date::modify($dt, false)->plus()->weeks(2);
        $this->assertEquals(
            '2019-10-14',
            $dt->format('Y-m-d')
        );
    }

    public function testAddSingleMonthInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-09-30');
        Date::modify($dt, false)->plus()->months(1);
        $this->assertEquals(
            '2019-10-30',
            $dt->format('Y-m-d')
        );
    }

    public function testAddTrickyMonthInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2020-01-29');
        Date::modify($dt, false)->plus()->months(1);
        $this->assertEquals(
            '2020-02-29',
            $dt->format('Y-m-d')
        );

        $dt = DateTime::createFromFormat('Y-m-d', '2020-01-29');
        Date::modify($dt, false)->plus()->months(1);
        $this->assertEquals(
            '2020-02-29',
            $dt->format('Y-m-d')
        );
    }

    public function testAddTwelveMonthsInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-09-30');
        Date::modify($dt, false)->plus()->months(12);
        $this->assertEquals(
            '2020-09-30',
            $dt->format('Y-m-d')
        );
    }

    public function testAddSingleYearInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-09-30');
        Date::modify($dt, false)->plus()->years(1);
        $this->assertEquals(
            '2020-09-30',
            $dt->format('Y-m-d')
        );
    }

    public function testAddFiftyYearsInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2049-01-01');
        Date::modify($dt, false)->plus()->years(50);
        $this->assertEquals(
            '2099-01-01',
            $dt->format('Y-m-d')
        );
    }
}
