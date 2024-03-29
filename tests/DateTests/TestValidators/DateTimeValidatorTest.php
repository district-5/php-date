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

namespace District5Tests\DateTests\TestValidators;

use DateInterval;
use DateTime;
use District5\Date\Date;
use PHPUnit\Framework\TestCase;

/**
 * Class DateTimeValidatorTest
 * @package District5Tests\DateTests\TestValidators
 */
class DateTimeValidatorTest extends TestCase
{
    public function testIsTodayTomorrowYesterday()
    {
        $newer = new DateTime();
        $tomorrow = clone $newer;
        $tomorrow->add(new DateInterval('P1D'));
        $older = clone $newer;
        $older->sub(new DateInterval('P1D'));
        $twoDaysAgo = clone $newer;
        $twoDaysAgo->sub(new DateInterval('P2D'));
        $between = clone $newer;
        $between->sub(new DateInterval('PT1H'));

        $this->assertTrue(Date::validateObject($newer)->isToday());
        $this->assertTrue(Date::validateObject($older)->isYesterday());
        $this->assertTrue(Date::validateObject($tomorrow)->isTomorrow());
    }

    public function testDateTimeBetween()
    {
        $fakeNow = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');

        $dateOne = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-18 23:59:59.999');
        $dateTwo = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-22 00:00:00.000');
        $this->assertTrue(Date::validateObject($fakeNow)->isBetween($dateOne, $dateTwo));
        $this->assertTrue(Date::validateObject($fakeNow)->isBetween($dateTwo, $dateOne));
        $this->assertFalse(Date::validateObject($dateOne)->isBetween($dateTwo, $dateTwo));
        $this->assertTrue(Date::validateObject($dateOne)->isBetween($dateOne, $dateOne));
    }

    public function testIsAM()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $this->assertFalse(
            Date::validateObject($date)->isAM()
        );
        $date2 = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 11:03:40.123');
        $this->assertTrue(
            Date::validateObject($date2)->isAM()
        );
    }

    public function testIsPM()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $this->assertTrue(
            Date::validateObject($date)->isPM()
        );
        $date2 = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 11:03:40.123');
        $this->assertFalse(
            Date::validateObject($date2)->isPM()
        );
    }

    public function testDateOlderThan()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $this->assertTrue(
            Date::validateObject($date)->isOlderThan(
                DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 23:03:40.123')
            )
        );
        $this->assertFalse(
            Date::validateObject($date)->isOlderThan(
                DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123')
            )
        );
    }

    public function testDateNewerThan()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $this->assertTrue(
            Date::validateObject($date)->isNewerThan(
                DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 21:03:40.123')
            )
        );
        $this->assertFalse(
            Date::validateObject($date)->isNewerThan(
                DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 23:13:40.123')
            )
        );
    }

    public function testDateOlderThanOrEqualTo()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $this->assertTrue(
            Date::validateObject($date)->isOlderThanOrEqualTo(
                DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 23:03:40.123')
            )
        );
        $this->assertFalse(
            Date::validateObject($date)->isOlderThanOrEqualTo(
                DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-19 23:03:40.123')
            )
        );
        $this->assertTrue(
            Date::validateObject($date)->isOlderThanOrEqualTo(
                $date
            )
        );
    }

    public function testDateNewerThanOrEqualTo()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $this->assertTrue(
            Date::validateObject($date)->isNewerThanOrEqualTo(
                DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 21:03:40.123')
            )
        );
        $this->assertFalse(
            Date::validateObject($date)->isNewerThanOrEqualTo(
                DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 23:13:40.123')
            )
        );
        $this->assertTrue(
            Date::validateObject($date)->isNewerThanOrEqualTo(
                $date
            )
        );
    }

    public function testHourLessThan()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $this->assertTrue(
            Date::validateObject($date)->isHourLessThan(23)
        );
        $date2 = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 11:03:40.123');
        $this->assertFalse(
            Date::validateObject($date2)->isHourLessThan(9)
        );
    }

    public function testHourLessThanOrEqualTo()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $this->assertTrue(
            Date::validateObject($date)->isHourLessThanOrEqualTo(23)
        );
        $date2 = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $this->assertTrue(
            Date::validateObject($date2)->isHourLessThanOrEqualTo(22)
        );
        $date3 = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $this->assertFalse(
            Date::validateObject($date3)->isHourLessThanOrEqualTo(21)
        );
    }

    public function testHourGreaterThan()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $this->assertTrue(
            Date::validateObject($date)->isHourGreaterThan(21)
        );
        $date2 = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 11:03:40.123');
        $this->assertFalse(
            Date::validateObject($date2)->isHourGreaterThan(12)
        );
        $date3 = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 11:03:40.123');
        $this->assertFalse(
            Date::validateObject($date3)->isHourGreaterThan(12)
        );
    }

    public function testHourGreaterThanOrEqualTo()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $this->assertTrue(
            Date::validateObject($date)->isHourGreaterThanOrEqualTo(21)
        );
        $date2 = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 11:03:40.123');
        $this->assertTrue(
            Date::validateObject($date2)->isHourGreaterThanOrEqualTo(11)
        );
        $date3 = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 11:03:40.123');
        $this->assertFalse(
            Date::validateObject($date3)->isHourGreaterThanOrEqualTo(12)
        );
    }

    public function testMonths()
    {
        $this->assertFalse(Date::validateObject(DateTime::createFromFormat('Y-m-d', '2019-02-15'))->isJanuary());
        $this->assertFalse(Date::validateObject(DateTime::createFromFormat('Y-m-d', '2019-03-15'))->isFebruary());
        $this->assertFalse(Date::validateObject(DateTime::createFromFormat('Y-m-d', '2019-04-15'))->isMarch());
        $this->assertFalse(Date::validateObject(DateTime::createFromFormat('Y-m-d', '2019-05-15'))->isApril());
        $this->assertFalse(Date::validateObject(DateTime::createFromFormat('Y-m-d', '2019-06-15'))->isMay());
        $this->assertFalse(Date::validateObject(DateTime::createFromFormat('Y-m-d', '2019-07-15'))->isJune());
        $this->assertFalse(Date::validateObject(DateTime::createFromFormat('Y-m-d', '2019-08-15'))->isJuly());
        $this->assertFalse(Date::validateObject(DateTime::createFromFormat('Y-m-d', '2019-09-15'))->isAugust());
        $this->assertFalse(Date::validateObject(DateTime::createFromFormat('Y-m-d', '2019-10-15'))->isSeptember());
        $this->assertFalse(Date::validateObject(DateTime::createFromFormat('Y-m-d', '2019-11-15'))->isOctober());
        $this->assertFalse(Date::validateObject(DateTime::createFromFormat('Y-m-d', '2019-12-15'))->isNovember());
        $this->assertFalse(Date::validateObject(DateTime::createFromFormat('Y-m-d', '2019-01-15'))->isDecember());
    }

    public function testDays()
    {
        $this->assertFalse(Date::validateObject(DateTime::createFromFormat('Y-m-d', '2019-04-07'))->isMonday());
        $this->assertFalse(Date::validateObject(DateTime::createFromFormat('Y-m-d', '2019-04-08'))->isTuesday());
        $this->assertFalse(Date::validateObject(DateTime::createFromFormat('Y-m-d', '2019-04-09'))->isWednesday());
        $this->assertFalse(Date::validateObject(DateTime::createFromFormat('Y-m-d', '2019-04-10'))->isThursday());
        $this->assertFalse(Date::validateObject(DateTime::createFromFormat('Y-m-d', '2019-04-11'))->isFriday());
        $this->assertFalse(Date::validateObject(DateTime::createFromFormat('Y-m-d', '2019-04-12'))->isSaturday());
        $this->assertFalse(Date::validateObject(DateTime::createFromFormat('Y-m-d', '2019-04-13'))->isSunday());
    }

    public function testLeapYear()
    {
        $this->assertFalse(Date::validateObject(DateTime::createFromFormat('Y-m-d', '1997-12-15'))->isLeapYear());
        $this->assertFalse(Date::validateObject(DateTime::createFromFormat('Y-m-d', '2001-12-15'))->isLeapYear());
        $this->assertFalse(Date::validateObject(DateTime::createFromFormat('Y-m-d', '2005-12-15'))->isLeapYear());
    }

    public function testSameDay()
    {
        $now = Date::nowDefault();
        $tomorrow = Date::modify(Date::nowDefault())->plus()->days(1);
        $otherNow = Date::nowDefault();
        $this->assertTrue(Date::validateObject($now)->isSameDay($otherNow));
        $this->assertFalse(Date::validateObject($now)->isSameDay($tomorrow));
    }

    public function testFutureAndPast()
    {
        $date = Date::modify(Date::nowDefault())->minus()->days(1);
        $this->assertTrue(Date::validateObject($date)->isPast());
        $this->assertFalse(Date::validateObject($date)->isFuture());

        $date = Date::modify(Date::nowDefault())->plus()->days(1);
        $this->assertTrue(Date::validateObject($date)->isFuture());
        $this->assertFalse(Date::validateObject($date)->isPast());
    }

    public function testIsWeekend()
    {
        $date = Date::createYMDHISM(2019, 4, 6, 12);
        $this->assertTrue(Date::validateObject($date)->isWeekend());
        $date = Date::createYMDHISM(2019, 4, 7, 12);
        $this->assertTrue(Date::validateObject($date)->isWeekend());
        $date = Date::createYMDHISM(2019, 4, 8, 12);
        $this->assertFalse(Date::validateObject($date)->isWeekend());
        $date = Date::createYMDHISM(2019, 4, 9, 12);
        $this->assertFalse(Date::validateObject($date)->isWeekend());
        $date = Date::createYMDHISM(2019, 4, 10, 12);
        $this->assertFalse(Date::validateObject($date)->isWeekend());
        $date = Date::createYMDHISM(2019, 4, 11, 12);
        $this->assertFalse(Date::validateObject($date)->isWeekend());
        $date = Date::createYMDHISM(2019, 4, 12, 12);
        $this->assertFalse(Date::validateObject($date)->isWeekend());
    }

    public function testIsWeekday()
    {
        $date = Date::createYMDHISM(2019, 4, 6, 12);
        $this->assertFalse(Date::validateObject($date)->isWeekday());
        $date = Date::createYMDHISM(2019, 4, 7, 12);
        $this->assertFalse(Date::validateObject($date)->isWeekday());
        $date = Date::createYMDHISM(2019, 4, 8, 12);
        $this->assertTrue(Date::validateObject($date)->isWeekday());
        $date = Date::createYMDHISM(2019, 4, 9, 12);
        $this->assertTrue(Date::validateObject($date)->isWeekday());
        $date = Date::createYMDHISM(2019, 4, 10, 12);
        $this->assertTrue(Date::validateObject($date)->isWeekday());
        $date = Date::createYMDHISM(2019, 4, 11, 12);
        $this->assertTrue(Date::validateObject($date)->isWeekday());
        $date = Date::createYMDHISM(2019, 4, 12, 12);
        $this->assertTrue(Date::validateObject($date)->isWeekday());
    }
}
