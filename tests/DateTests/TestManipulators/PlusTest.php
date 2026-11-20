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
use DateTimeZone;
use District5\Date\Date;
use District5\Date\Manipulators\Plus;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

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

        $dt = DateTime::createFromFormat('Y-m-d', '2019-01-29');
        $this->assertEquals(
            '2019-02-28',
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

        $dt = DateTime::createFromFormat('Y-m-d', '2019-01-29');
        Date::modify($dt, false)->plus()->months(1);
        $this->assertEquals(
            '2019-02-28',
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

    public function testAddSingleDecadeInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-09-30');
        Date::modify($dt, false)->plus()->decades(1);
        $this->assertEquals(
            '2029-09-30',
            $dt->format('Y-m-d')
        );
    }

    public function testAddFourDecadesInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-09-30');
        Date::modify($dt, false)->plus()->decades(4);
        $this->assertEquals(
            '2059-09-30',
            $dt->format('Y-m-d')
        );
    }

    public function testAddSingleCenturyInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-09-30');
        Date::modify($dt, false)->plus()->centuries(1);
        $this->assertEquals(
            '2119-09-30',
            $dt->format('Y-m-d')
        );
    }

    public function testAddFourCenturiesInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-09-30');
        Date::modify($dt, false)->plus()->centuries(4);
        $this->assertEquals(
            '2419-09-30',
            $dt->format('Y-m-d')
        );
    }

    public function testAddOneMillenniaInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-09-30');
        Date::modify($dt, false)->plus()->millennia(1);
        $this->assertEquals(
            '3019-09-30',
            $dt->format('Y-m-d')
        );
    }

    public function testAddFourMillenniaInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d', '2019-09-30');
        Date::modify($dt, false)->plus()->millennia(4);
        $this->assertEquals(
            '6019-09-30',
            $dt->format('Y-m-d')
        );
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testRunWithInvalidString()
    {
        $class = new ReflectionClass(Plus::class);
        $method = $class->getMethod('run');

        $date = DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 00:15:10');
        $minus = new Plus($date);
        $result = $method->invokeArgs($minus, ['run', 'this isn\'t valid']);
        $this->assertFalse($result);
    }

    public static function plusEdgeCaseProvider(): array
    {
        return [
            'months: Jan 31 + 1 clamps to Feb 28' => ['months', [1], '2025-01-31 10:00:00.000000', 'UTC', '2025-02-28 10:00:00.000000 UTC'],
            'months: Jan 31 + 1 clamps to Feb 29 in leap year' => ['months', [1], '2024-01-31 10:00:00.000000', 'UTC', '2024-02-29 10:00:00.000000 UTC'],
            'months: Jan 30 + 1 clamps to Feb 28' => ['months', [1], '2025-01-30 00:00:00.000000', 'UTC', '2025-02-28 00:00:00.000000 UTC'],
            'months: Aug 31 + 1 clamps to Sep 30' => ['months', [1], '2025-08-31 00:00:00.000000', 'UTC', '2025-09-30 00:00:00.000000 UTC'],
            'months: Feb 29 + 12 clamps to Feb 28' => ['months', [12], '2024-02-29 00:00:00.000000', 'UTC', '2025-02-28 00:00:00.000000 UTC'],
            'months: Jan 31 + 13 crosses year and clamps' => ['months', [13], '2025-01-31 00:00:00.000000', 'UTC', '2026-02-28 00:00:00.000000 UTC'],
            'months: Dec 15 + 1 crosses year' => ['months', [1], '2025-12-15 00:00:00.000000', 'UTC', '2026-01-15 00:00:00.000000 UTC'],
            'months: zero is a no-op' => ['months', [0], '2025-01-31 00:00:00.000000', 'UTC', '2025-01-31 00:00:00.000000 UTC'],
            'months: negative uses absolute value' => ['months', [-1], '2025-01-15 00:00:00.000000', 'UTC', '2025-02-15 00:00:00.000000 UTC'],
            'days: year boundary' => ['days', [1], '2025-12-31 23:00:00.000000', 'UTC', '2026-01-01 23:00:00.000000 UTC'],
            'days: into leap day' => ['days', [1], '2024-02-28 00:00:00.000000', 'UTC', '2024-02-29 00:00:00.000000 UTC'],
            'days: 365 in a leap year' => ['days', [365], '2024-01-01 00:00:00.000000', 'UTC', '2024-12-31 00:00:00.000000 UTC'],
            'days: zero is a no-op' => ['days', [0], '2025-06-01 12:00:00.000000', 'UTC', '2025-06-01 12:00:00.000000 UTC'],
            'days: negative uses absolute value' => ['days', [-3], '2025-01-15 00:00:00.000000', 'UTC', '2025-01-18 00:00:00.000000 UTC'],
            'days: across spring DST keeps wall clock' => ['days', [1], '2025-03-29 12:00:00.000000', 'Europe/London', '2025-03-30 12:00:00.000000 BST'],
            'days: across autumn DST keeps wall clock' => ['days', [1], '2025-10-25 12:00:00.000000', 'Europe/London', '2025-10-26 12:00:00.000000 GMT'],
            'weeks: negative uses absolute value' => ['weeks', [-1], '2025-01-15 00:00:00.000000', 'UTC', '2025-01-22 00:00:00.000000 UTC'],
            'weeks: year boundary' => ['weeks', [1], '2025-12-28 00:00:00.000000', 'UTC', '2026-01-04 00:00:00.000000 UTC'],
            'hours: spring DST gap is elapsed time' => ['hours', [1], '2025-03-30 00:30:00.000000', 'Europe/London', '2025-03-30 02:30:00.000000 BST'],
            'hours: autumn DST repeated hour is elapsed time' => ['hours', [2], '2025-10-26 00:30:00.000000', 'Europe/London', '2025-10-26 01:30:00.000000 GMT'],
            'hours: negative uses absolute value' => ['hours', [-2], '2025-01-15 10:00:00.000000', 'UTC', '2025-01-15 12:00:00.000000 UTC'],
            'hours: day boundary' => ['hours', [25], '2025-01-15 10:00:00.000000', 'UTC', '2025-01-16 11:00:00.000000 UTC'],
            'minutes: year boundary' => ['minutes', [1], '2025-12-31 23:59:00.000000', 'UTC', '2026-01-01 00:00:00.000000 UTC'],
            'minutes: negative uses absolute value' => ['minutes', [-90], '2025-01-15 10:00:00.000000', 'UTC', '2025-01-15 11:30:00.000000 UTC'],
            'seconds: year boundary' => ['seconds', [1], '2025-12-31 23:59:59.000000', 'UTC', '2026-01-01 00:00:00.000000 UTC'],
            'seconds: zero is a no-op' => ['seconds', [0], '2025-12-31 23:59:59.500000', 'UTC', '2025-12-31 23:59:59.500000 UTC'],
            'milliseconds: carry into next year' => ['milliseconds', [1], '2025-12-31 23:59:59.999500', 'UTC', '2026-01-01 00:00:00.000500 UTC'],
            'milliseconds: negative uses absolute value' => ['milliseconds', [-5], '2025-01-15 00:00:00.000000', 'UTC', '2025-01-15 00:00:00.005000 UTC'],
            'microseconds: carry into next year' => ['microseconds', [1], '2025-12-31 23:59:59.999999', 'UTC', '2026-01-01 00:00:00.000000 UTC'],
            'microseconds: negative uses absolute value' => ['microseconds', [-7], '2025-01-15 00:00:00.000000', 'UTC', '2025-01-15 00:00:00.000007 UTC'],
            'hoursAndMinutes: crosses midnight' => ['hoursAndMinutes', [1, 30], '2025-12-31 23:00:00.000000', 'UTC', '2026-01-01 00:30:00.000000 UTC'],
            'hoursAndMinutesAndSeconds: crosses midnight' => ['hoursAndMinutesAndSeconds', [0, 59, 61], '2025-12-31 23:00:00.000000', 'UTC', '2026-01-01 00:00:01.000000 UTC'],
            'years: Dec 31' => ['years', [1], '2024-12-31 00:00:00.000000', 'UTC', '2025-12-31 00:00:00.000000 UTC'],
            'years: zero is a no-op' => ['years', [0], '2024-12-31 00:00:00.000000', 'UTC', '2024-12-31 00:00:00.000000 UTC'],
            'decades: negative uses absolute value' => ['decades', [-1], '2025-01-15 00:00:00.000000', 'UTC', '2035-01-15 00:00:00.000000 UTC'],
            'centuries: negative uses absolute value' => ['centuries', [-1], '2025-01-15 00:00:00.000000', 'UTC', '2125-01-15 00:00:00.000000 UTC'],
            'millennia: negative uses absolute value' => ['millennia', [-1], '2025-01-15 00:00:00.000000', 'UTC', '3025-01-15 00:00:00.000000 UTC'],
        ];
    }

    #[DataProvider('plusEdgeCaseProvider')]
    public function testPlusEdgeCasesDoNotMutateInput(string $method, array $args, string $input, string $timezone, string $expected)
    {
        $format = 'Y-m-d H:i:s.u T';
        $dt = new DateTime($input, new DateTimeZone($timezone));
        $before = $dt->format($format);

        $result = Date::modify($dt)->plus()->{$method}(...$args);

        $this->assertInstanceOf(DateTime::class, $result);
        $this->assertNotSame($dt, $result);
        $this->assertEquals($expected, $result->format($format));
        $this->assertEquals($before, $dt->format($format), 'Input DateTime was mutated in clone mode');
    }

    #[DataProvider('plusEdgeCaseProvider')]
    public function testPlusEdgeCasesInPlaceMutateInput(string $method, array $args, string $input, string $timezone, string $expected)
    {
        $format = 'Y-m-d H:i:s.u T';
        $dt = new DateTime($input, new DateTimeZone($timezone));

        $result = Date::modify($dt, false)->plus()->{$method}(...$args);

        $this->assertSame($dt, $result);
        $this->assertEquals($expected, $dt->format($format));
    }

    public static function plusOverflowProvider(): array
    {
        return [
            'hours' => ['hours', [PHP_INT_MIN]],
            'minutes' => ['minutes', [PHP_INT_MIN]],
            'seconds' => ['seconds', [PHP_INT_MIN]],
            'days' => ['days', [PHP_INT_MIN]],
            'years' => ['years', [PHP_INT_MIN]],
            'hoursAndMinutes (hours invalid)' => ['hoursAndMinutes', [PHP_INT_MIN, 1]],
            'hoursAndMinutes (minutes invalid)' => ['hoursAndMinutes', [1, PHP_INT_MIN]],
            'hoursAndMinutesAndSeconds (seconds invalid)' => ['hoursAndMinutesAndSeconds', [1, 1, PHP_INT_MIN]],
            'hoursAndMinutesAndSeconds (minutes invalid)' => ['hoursAndMinutesAndSeconds', [1, PHP_INT_MIN, 1]],
        ];
    }

    /**
     * abs(PHP_INT_MIN) overflows to a float, which produces an invalid DateInterval spec, so the
     * manipulator must report failure with false rather than throwing.
     */
    #[DataProvider('plusOverflowProvider')]
    public function testPlusReturnsFalseWhenIntervalCannotBeBuilt(string $method, array $args)
    {
        $dt = new DateTime('2025-01-15 00:00:00', new DateTimeZone('UTC'));
        $this->assertFalse(Date::modify($dt)->plus()->{$method}(...$args));
        $this->assertEquals('2025-01-15 00:00:00', $dt->format('Y-m-d H:i:s'));

        // In place mode must not partially apply (e.g. the hours) before failing.
        $this->assertFalse(Date::modify($dt, false)->plus()->{$method}(...$args));
        $this->assertEquals('2025-01-15 00:00:00', $dt->format('Y-m-d H:i:s'));
    }

    public function testPlusChainedCallsOnSameInstanceAccumulate()
    {
        $dt = new DateTime('2025-01-31 00:00:00', new DateTimeZone('UTC'));
        $plus = Date::modify($dt)->plus();
        $plus->months(1);
        $result = $plus->days(1);
        $this->assertEquals('2025-03-01', $result->format('Y-m-d'));
        $this->assertEquals('2025-01-31', $dt->format('Y-m-d'));
    }
}
