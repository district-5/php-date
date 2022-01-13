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

namespace District5Tests\DateTests\TestStaticData;

use DateTime;
use PHPUnit\Framework\TestCase;
use District5\Date\Date;

/**
 * Class MonthTest
 * @package District5Tests\DateTests\TestStaticData
 */
class MonthTest extends TestCase
{
    public function testBeginningOfMonths()
    {
        $this->assertEquals(
            '2020-01-01 00:00:00',
            Date::month()->firstDateInMonth(1, 2020)->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            '2020-02-01 00:00:00',
            Date::month()->firstDateInMonth(2, 2020)->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            '2020-12-01 00:00:00',
            Date::month()->firstDateInMonth(12, 2020)->format('Y-m-d H:i:s')
        );
    }

    public function testBeginningOfMonthsFromDateTime()
    {
        $this->assertEquals(
            '2020-01-01 00:00:00',
            Date::month()->firstDateInMonthFromDateTime(
                DateTime::createFromFormat('Y-m-d H:i:s', '2020-01-15 00:00:00')
            )->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            '2020-02-01 00:00:00',
            Date::month()->firstDateInMonthFromDateTime(
                DateTime::createFromFormat('Y-m-d H:i:s', '2020-02-15 00:00:00')
            )->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            '2020-12-01 00:00:00',
            Date::month()->firstDateInMonthFromDateTime(
                DateTime::createFromFormat('Y-m-d H:i:s', '2020-12-15 00:00:00')
            )->format('Y-m-d H:i:s')
        );
    }

    public function testLastOfMonths()
    {
        $this->assertEquals(
            '2020-01-31 00:00:00',
            Date::month()->lastDateInMonth(1, 2020)->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            '2020-02-29 00:00:00',
            Date::month()->lastDateInMonth(2, 2020)->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            '2019-02-28 00:00:00',
            Date::month()->lastDateInMonth(2, 2019)->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            '2020-12-31 00:00:00',
            Date::month()->lastDateInMonth(12, 2020)->format('Y-m-d H:i:s')
        );
    }

    public function testLastOfMonthsFromDateTime()
    {
        $this->assertEquals(
            '2020-01-31 00:00:00',
            Date::month()->lastDateInMonthFromDateTime(
                DateTime::createFromFormat('Y-m-d H:i:s', '2020-01-15 00:00:00')
            )->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            '2020-02-29 00:00:00',
            Date::month()->lastDateInMonthFromDateTime(
                DateTime::createFromFormat('Y-m-d H:i:s', '2020-02-15 00:00:00')
            )->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            '2019-02-28 00:00:00',
            Date::month()->lastDateInMonthFromDateTime(
                DateTime::createFromFormat('Y-m-d H:i:s', '2019-02-15 00:00:00')
            )->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            '2020-12-31 00:00:00',
            Date::month()->lastDateInMonthFromDateTime(
                DateTime::createFromFormat('Y-m-d H:i:s', '2020-12-15 00:00:00')
            )->format('Y-m-d H:i:s')
        );
    }

    public function testNumberDaysInMonth()
    {
        $dates = [
            '2020-01-31 00:00:00' => 31,
            '2020-01-15 00:00:00' => 31,
            '2020-11-31 00:00:00' => 31,
            '2020-02-15 00:00:00' => 29,
            '2019-02-15 00:00:00' => 28,
        ];
        foreach ($dates as $k => $v) {
            $dt = DateTime::createFromFormat('Y-m-d H:i:s', $k);
            $inst = Date::output($dt);
            $days = Date::month()->numberDaysInMonth(
                $inst->getMonth(),
                $inst->getYear()
            );
            $this->assertEquals($v, $days);

            $daysDt = Date::month()->numberDaysInMonthFromDateTime(
                $dt
            );
            $this->assertEquals($v, $daysDt);
        }
    }
}
