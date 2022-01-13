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

namespace District5Tests\DateTests\TestCalculators;

use DateTime;
use District5\Date\Date;
use PHPUnit\Framework\TestCase;

/**
 * Class DiffTest
 * @package District5Tests\DateTests\TestCalculators
 */
class DiffTest extends TestCase
{
    public function testDayDiff()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $dateAlmostSame = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 21:03:40.123');
        $date2 = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-22 22:03:40.123');
        $this->assertEquals(
            0,
            Date::diff($date)->days($dateAlmostSame)
        );
        $this->assertEquals(
            2,
            Date::diff($date)->days($date2)
        );
    }

    public function testWeekDiff()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $dateAlmostSame = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-05-20 21:03:40.123');
        $date2 = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-07-22 22:03:40.123');
        $this->assertEquals(
            8,
            Date::diff($date)->weeks($dateAlmostSame)
        );
        $this->assertEquals(
            17,
            Date::diff($date)->weeks($date2)
        );
    }

    public function testWeekDiffOverYear()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $dateInYear = DateTime::createFromFormat('Y-m-d H:i:s.u', '2020-03-20 22:03:40.123');
        $this->assertEquals(
            52,
            Date::diff($date)->weeks($dateInYear)
        );
    }

    public function testWeekDiffOverTwoYears()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $dateInYear = DateTime::createFromFormat('Y-m-d H:i:s.u', '2021-03-20 22:03:40.123');
        $this->assertEquals(
            104,
            Date::diff($date)->weeks($dateInYear)
        );
    }

    public function testMonthDiff()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $dateAlmostSame = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-05-20 21:03:40.123');
        $date2 = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-07-22 22:03:40.123');
        $this->assertEquals(
            1,
            Date::diff($date)->months($dateAlmostSame)
        );
        $this->assertEquals(
            4,
            Date::diff($date)->months($date2)
        );
    }

    public function testMonthDiffEighteenMonths()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $dateOther = DateTime::createFromFormat('Y-m-d H:i:s.u', '2020-09-20 22:03:40.123');
        $this->assertEquals(
            18,
            Date::diff($date)->months($dateOther)
        );
    }

    public function testYearDiff()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $nextYear = DateTime::createFromFormat('Y-m-d H:i:s.u', '2020-03-21 21:03:40.123');
        $this->assertEquals(
            1,
            Date::diff($date)->years($nextYear)
        );

        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $nextYearExact = DateTime::createFromFormat('Y-m-d H:i:s.u', '2020-03-20 22:03:40.123');
        $this->assertEquals(
            1,
            Date::diff($date)->years($nextYearExact)
        );

        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $nextYearPlusSixMonths = DateTime::createFromFormat('Y-m-d H:i:s.u', '2020-09-20 22:03:40.123');
        $this->assertEquals(
            1,
            Date::diff($date)->years($nextYearPlusSixMonths)
        );

        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $nextYearPlusEighteenMonths = DateTime::createFromFormat('Y-m-d H:i:s.u', '2021-09-20 22:03:40.123');
        $this->assertEquals(
            2,
            Date::diff($date)->years($nextYearPlusEighteenMonths)
        );

        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $nextYearPlusTwoYears = DateTime::createFromFormat('Y-m-d H:i:s.u', '2021-03-21 22:03:40.123');
        $this->assertEquals(
            2,
            Date::diff($date)->years($nextYearPlusTwoYears)
        );
    }

    public function testDecades()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $nextYear = DateTime::createFromFormat('Y-m-d H:i:s.u', '2020-03-21 21:03:40.123');
        $furtherInFutureYear = DateTime::createFromFormat('Y-m-d H:i:s.u', '2050-03-21 21:03:40.123');
        $this->assertEquals(
            0,
            Date::diff($date)->decades($nextYear)
        );
        $this->assertEquals(
            3,
            Date::diff($date)->decades($furtherInFutureYear)
        );
    }

    public function testCenturies()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $nextYear = DateTime::createFromFormat('Y-m-d H:i:s.u', '2020-03-21 21:03:40.123');
        $furtherInFutureYear = DateTime::createFromFormat('Y-m-d H:i:s.u', '2350-03-21 21:03:40.123');
        $this->assertEquals(
            0,
            Date::diff($date)->centuries($nextYear)
        );
        $this->assertEquals(
            3,
            Date::diff($date)->centuries($furtherInFutureYear)
        );
    }

    public function testMillennia()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $nextMillennia = DateTime::createFromFormat('Y-m-d H:i:s.u', '3020-03-21 21:03:40.123');
        $furtherInFutureYear = DateTime::createFromFormat('Y-m-d H:i:s.u', '5350-03-21 21:03:40.123');
        $this->assertEquals(
            1,
            Date::diff($date)->millennia($nextMillennia)
        );
        $this->assertEquals(
            3,
            Date::diff($date)->millennia($furtherInFutureYear)
        );
    }

    public function testDayDiffBothWays()
    {
        $newest = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $oldest = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-10 18:01:05.456');

        $this->assertEquals(
            10,
            Date::diff($newest)->days($oldest)
        );
        $this->assertEquals(
            10,
            Date::diff($oldest)->days($newest)
        );
    }

    public function testSecondDiffBothWays()
    {
        $newest = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $oldest = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:04:40.123');

        $this->assertEquals(
            60,
            Date::diff($newest)->seconds($oldest)
        );
        $this->assertEquals(
            60,
            Date::diff($oldest)->seconds($newest)
        );
    }

    public function testMinuteDiff()
    {
        $newest = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:01:40.123');
        $oldest = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:05:40.123');

        $this->assertEquals(
            4,
            Date::diff($newest)->minutes($oldest)
        );
        $this->assertEquals(
            4,
            Date::diff($oldest)->minutes($newest)
        );


        $newest2 = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:01:40.123');
        $oldest2 = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:10.123');
        $this->assertEquals(
            2,
            Date::diff($oldest2)->minutes($newest2)
        );
    }

    public function testHourDiff()
    {
        $newest = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:01:40.123');
        $oldest = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 23:05:40.123');

        $this->assertEquals(
            1,
            Date::diff($newest)->hours($oldest)
        );
        $this->assertEquals(
            1,
            Date::diff($oldest)->hours($newest)
        );


        $newest2 = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:01:40.123');
        $oldest2 = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 20:03:10.123');
        $this->assertEquals(
            2,
            Date::diff($oldest2)->hours($newest2)
        );
    }
}
