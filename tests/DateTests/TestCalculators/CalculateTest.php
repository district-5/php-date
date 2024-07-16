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
use District5\Date\Calculators\Calculate;
use District5\Date\Date;
use PHPUnit\Framework\TestCase;

/**
 * Class CalculateTest
 * @package District5Tests\DateTests\TestCalculators
 */
class CalculateTest extends TestCase
{
    public function testDaysLeftInMonthStatic()
    {
        $this->assertEquals(26, Calculate::numberDaysLeftInMonthByGivenValues(
            5,
            1,
            2020
        ));
    }

    public function testDateOlderThanInCalculate()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $this->assertTrue(
            Date::calculate($date)->isOlderThan(
                DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 23:03:40.123')
            )
        );
        $this->assertFalse(
            Date::calculate($date)->isOlderThan(
                DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123')
            )
        );
    }

    public function testDateNewerThanInCalculate()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $this->assertTrue(
            Date::calculate($date)->isNewerThan(
                DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 21:03:40.123')
            )
        );
        $this->assertFalse(
            Date::calculate($date)->isNewerThan(
                DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 23:13:40.123')
            )
        );
    }

    public function testDaysLeftInMonthNormal()
    {
        $now = DateTime::createFromFormat('Y-m-d H:i:s', '2020-01-05 01:01:00');
        $this->assertEquals(26, Date::calculate($now)->numberDaysLeftInMonth());
    }

    public function testDaysInMonthStatic()
    {
        $this->assertEquals(31, Calculate::numberDaysInMonthByGivenValues(1, 2020));
    }

    public function testDaysInMonthNormal()
    {
        $now = DateTime::createFromFormat('Y-m-d H:i:s', '2020-01-05 01:01:00');
        $this->assertEquals(31, Date::calculate($now)->numberDaysInMonth());
    }

    public function testDaysLeftInMonthLeapYear()
    {
        $this->assertEquals(27, Calculate::numberDaysLeftInMonthByGivenValues(1, 2, 2019));

        $this->assertEquals(28, Calculate::numberDaysLeftInMonthByGivenValues(1, 2, 2020));
    }

    public function testDaysLeftInMonthLeapYearNormal()
    {
        $now = DateTime::createFromFormat('Y-m-d H:i:s', '2019-02-01 01:01:00');
        $this->assertEquals(27, Date::calculate($now)->numberDaysLeftInMonth());

        $now = DateTime::createFromFormat('Y-m-d H:i:s', '2020-02-01 01:01:00');
        $this->assertEquals(28, Date::calculate($now)->numberDaysLeftInMonth());
    }

    public function testDaysInMonthLeapYearStatic()
    {
        $this->assertEquals(29, Calculate::numberDaysInMonthByGivenValues(2, 2020));

        $this->assertEquals(28, Calculate::numberDaysInMonthByGivenValues(2, 2019));
    }

    public function testDaysInMonthLeapYearNormal()
    {
        $now = DateTime::createFromFormat('Y-m-d H:i:s', '2019-02-05 01:01:00');
        $this->assertEquals(28, Date::calculate($now)->numberDaysInMonth());

        $now = DateTime::createFromFormat('Y-m-d H:i:s', '2020-02-05 01:01:00');
        $this->assertEquals(29, Date::calculate($now)->numberDaysInMonth());
    }

    public function testCalculateHoursMinutesSeconds()
    {
        $dateOne = Date::createYMDHISM(2024, 01, 01, 12, 30, 45);
        $dateTwo = Date::createYMDHISM(2024, 01, 01, 13, 31, 22);
        $this->assertEquals(1, Date::calculate($dateOne)->hours($dateTwo));
        $this->assertEquals(61, Date::calculate($dateOne)->minutes($dateTwo));
        $this->assertEquals(3637, Date::calculate($dateOne)->seconds($dateTwo));

        $dateOne = Date::createYMDHISM(2024, 01, 01, 12, 30, 45);
        $dateTwo = Date::createYMDHISM(2024, 01, 01, 12, 31, 22);
        $this->assertEquals(0, Date::calculate($dateOne)->hours($dateTwo));
        $this->assertEquals(1, Date::calculate($dateOne)->minutes($dateTwo));
        $this->assertEquals(37, Date::calculate($dateOne)->seconds($dateTwo));

        $dateOne = Date::createYMDHISM(2024, 01, 01, 12, 30, 45);
        $dateTwo = Date::createYMDHISM(2024, 01, 01, 8, 31, 22); // reversed
        $this->assertEquals(4, Date::calculate($dateOne)->hours($dateTwo));
        $this->assertEquals(239, Date::calculate($dateOne)->minutes($dateTwo));
        $this->assertEquals(14363, Date::calculate($dateOne)->seconds($dateTwo));

        $dateOne = Date::createYMDHISM(2024, 01, 01, 12, 30, 45);
        $dateTwo = Date::createYMDHISM(2024, 01, 01, 12, 30, 45); // same
        $this->assertEquals(0, Date::calculate($dateOne)->hours($dateTwo));
        $this->assertEquals(0, Date::calculate($dateOne)->minutes($dateTwo));
        $this->assertEquals(0, Date::calculate($dateOne)->seconds($dateTwo));
    }

    public function testIsOlderThanOrEqualTo()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $this->assertTrue(
            Date::calculate($date)->isOlderThanOrEqualTo(
                DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 23:03:40.123')
            )
        );

        $this->assertFalse(
            Date::calculate($date)->isOlderThanOrEqualTo(
                DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.122')
            )
        );
    }

    public function testIsNewerThanOrEqualTo()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $this->assertTrue(
            Date::calculate($date)->isNewerThanOrEqualTo(
                DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 21:03:40.123')
            )
        );

        $this->assertFalse(
            Date::calculate($date)->isNewerThanOrEqualTo(
                DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.124')
            )
        );
    }
}
