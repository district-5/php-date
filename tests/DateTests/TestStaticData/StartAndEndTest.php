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

namespace DateTests\TestStaticData;

use DateTime;
use DateTimeZone;
use District5\Date\Date;
use PHPUnit\Framework\TestCase;

/**
 * Class StartAndEndTest
 * @package District5Tests\DateTests\TestStaticData
 */
class StartAndEndTest extends TestCase
{
    public function testStartOfMonthFromYearMonth()
    {
        $startAndEnd = Date::startAndEnd();
        $this->assertEquals(
            '2045-01-01 00:00:00',
            $startAndEnd->startOfMonthFromYearMonth(2045, 1)->format('Y-m-d H:i:s')
        );
    }

    public function testStartOfMonthFromDateTime()
    {
        $startAndEnd = Date::startAndEnd();
        $this->assertEquals(
            '2045-01-01 00:00:00',
            $startAndEnd->startOfMonthFromDateTime(new DateTime('2045-01-01 00:00:00'))->format('Y-m-d H:i:s')
        );
    }

    public function testEndOfMonthFromYearMonth()
    {
        $startAndEnd = Date::startAndEnd();
        $this->assertEquals(
            '2045-01-31 23:59:59',
            $startAndEnd->endOfMonthFromYearMonth(2045, 1)->format('Y-m-d H:i:s')
        );
    }

    public function testEndOfMonthFromDateTime()
    {
        $startAndEnd = Date::startAndEnd();
        $this->assertEquals(
            '2045-01-31 23:59:59',
            $startAndEnd->endOfMonthFromDateTime(new DateTime('2045-01-01 00:00:00'))->format('Y-m-d H:i:s')
        );
    }

    public function testNonLeapYearFebruaryStart()
    {
        $startAndEnd = Date::startAndEnd();
        $this->assertEquals(
            '2023-02-01 00:00:00',
            $startAndEnd->startOfMonthFromYearMonth(2023, 2)->format('Y-m-d H:i:s')
        );
    }

    public function testNonLeapYearFebruaryEnd()
    {
        $startAndEnd = Date::startAndEnd();
        $this->assertEquals(
            '2023-02-28 23:59:59',
            $startAndEnd->endOfMonthFromYearMonth(2023, 2)->format('Y-m-d H:i:s')
        );
    }

    public function testLeapYearFebruaryStart()
    {
        $startAndEnd = Date::startAndEnd();
        $this->assertEquals(
            '2024-02-01 00:00:00',
            $startAndEnd->startOfMonthFromYearMonth(2024, 2)->format('Y-m-d H:i:s')
        );
    }

    public function testLeapYearFebruaryEnd()
    {
        $startAndEnd = Date::startAndEnd();
        $this->assertEquals(
            '2024-02-29 23:59:59',
            $startAndEnd->endOfMonthFromYearMonth(2024, 2)->format('Y-m-d H:i:s')
        );
    }

    public function testStartOfDayFromDateTime()
    {
        $startAndEnd = Date::startAndEnd();
        $this->assertEquals(
            '2045-01-01 00:00:00',
            $startAndEnd->startOfDayFromDateTime(new DateTime('2045-01-01 12:34:56'))->format('Y-m-d H:i:s')
        );
    }

    public function testEndOfDayFromDateTime()
    {
        $startAndEnd = Date::startAndEnd();
        $this->assertEquals(
            '2045-01-01 23:59:59',
            $startAndEnd->endOfDayFromDateTime(new DateTime('2045-01-01 12:34:56'))->format('Y-m-d H:i:s')
        );
    }

    public function testStartOfDayFromDateTimeWithTimezone()
    {
        $startAndEnd = Date::startAndEnd();
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->assertEquals(
            '2045-01-01 00:00:00',
            $startAndEnd->startOfDayFromDateTime(new DateTime('2045-01-01 12:34:56', new DateTimeZone('UTC')))->format('Y-m-d H:i:s')
        );
    }

    public function testEndOfDayFromDateTimeWithTimezone()
    {
        $startAndEnd = Date::startAndEnd();
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->assertEquals(
            '2045-01-01 23:59:59',
            $startAndEnd->endOfDayFromDateTime(new DateTime('2045-01-01 12:34:56', new DateTimeZone('UTC')))->format('Y-m-d H:i:s')
        );
    }

    public function testStartOfYearFromYear()
    {
        $startAndEnd = Date::startAndEnd();
        $this->assertEquals(
            '2045-01-01 00:00:00',
            $startAndEnd->startOfYearFromYear(2045)->format('Y-m-d H:i:s')
        );
    }

    public function testStartOfYearFromYearFromDateTime()
    {
        $startAndEnd = Date::startAndEnd();
        $this->assertEquals(
            '2045-01-01 00:00:00',
            $startAndEnd->startOfYearFromDateTime(new DateTime('2045-12-31 23:59:59'))->format('Y-m-d H:i:s')
        );
    }

    public function testEndOfYearFromYear()
    {
        $startAndEnd = Date::startAndEnd();
        $this->assertEquals(
            '2045-12-31 23:59:59',
            $startAndEnd->endOfYearFromYear(2045)->format('Y-m-d H:i:s')
        );
    }

    public function testEndOfYearFromDateTime()
    {
        $startAndEnd = Date::startAndEnd();
        $this->assertEquals(
            '2045-12-31 23:59:59',
            $startAndEnd->endOfYearFromDateTime(new DateTime('2045-01-01 00:00:00'))->format('Y-m-d H:i:s')
        );
    }

    public function testStartOfToday()
    {
        $startAndEnd = Date::startAndEnd();
        $this->assertEquals(
            (new DateTime())->format('Y-m-d 00:00:00'),
            $startAndEnd->startOfToday()->format('Y-m-d H:i:s')
        );
    }

    public function testEndOfToday()
    {
        $startAndEnd = Date::startAndEnd();
        $this->assertEquals(
            (new DateTime())->format('Y-m-d 23:59:59'),
            $startAndEnd->endOfToday()->format('Y-m-d H:i:s')
        );
    }
}
