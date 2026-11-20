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
use DateTimeZone;
use District5\Date\Calculators\Calculate;
use District5\Date\Date;
use PHPUnit\Framework\Attributes\DataProvider;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Class CalculateEdgeCaseTest
 * @package District5Tests\DateTests\TestCalculators
 */
class CalculateEdgeCaseTest extends TestCase
{
    private static function dt(string $value, string $tz = 'UTC'): DateTime
    {
        return new DateTime($value, new DateTimeZone($tz));
    }

    /**
     * [month, year, expected days]
     */
    public static function daysInMonthProvider(): array
    {
        return [
            'Jan' => [1, 2023, 31],
            'Feb non leap' => [2, 2023, 28],
            'Feb leap' => [2, 2024, 29],
            'Feb 1900 (div by 100, not leap)' => [2, 1900, 28],
            'Feb 2000 (div by 400, leap)' => [2, 2000, 29],
            'Mar' => [3, 2023, 31],
            'Apr' => [4, 2023, 30],
            'May' => [5, 2023, 31],
            'Jun' => [6, 2023, 30],
            'Jul' => [7, 2023, 31],
            'Aug' => [8, 2023, 31],
            'Sep' => [9, 2023, 30],
            'Oct' => [10, 2023, 31],
            'Nov' => [11, 2023, 30],
            'Dec' => [12, 2023, 31],
        ];
    }

    #[DataProvider('daysInMonthProvider')]
    public function testNumberDaysInMonth(int $month, int $year, int $expected)
    {
        $this->assertSame($expected, Calculate::numberDaysInMonthByGivenValues($month, $year));

        $dt = self::dt(sprintf('%04d-%02d-15 12:00:00', $year, $month));
        $this->assertSame($expected, Date::calculate($dt)->numberDaysInMonth());
    }

    #[DataProvider('daysInMonthProvider')]
    public function testNumberDaysLeftInMonthAtBoundaries(int $month, int $year, int $daysInMonth)
    {
        $this->assertSame($daysInMonth - 1, Calculate::numberDaysLeftInMonthByGivenValues(1, $month, $year));
        $this->assertSame(0, Calculate::numberDaysLeftInMonthByGivenValues($daysInMonth, $month, $year));

        $first = self::dt(sprintf('%04d-%02d-01 00:00:00', $year, $month));
        $last = self::dt(sprintf('%04d-%02d-%02d 23:59:59', $year, $month, $daysInMonth));
        $this->assertSame($daysInMonth - 1, Date::calculate($first)->numberDaysLeftInMonth());
        $this->assertSame(0, Date::calculate($last)->numberDaysLeftInMonth());
    }

    public function testNumberDaysInMonthUsesTheDateTimesOwnTimezone()
    {
        // 2024-03-01 00:30 in Tokyo is still 2024-02-29 in UTC; the local calendar month (March) is used.
        $tokyo = self::dt('2024-03-01 00:30:00', 'Asia/Tokyo');
        $this->assertSame(31, Date::calculate($tokyo)->numberDaysInMonth());
        $this->assertSame(30, Date::calculate($tokyo)->numberDaysLeftInMonth());

        // 2024-02-29 23:30 in New York is already 2024-03-01 in UTC.
        $newYork = self::dt('2024-02-29 23:30:00', 'America/New_York');
        $this->assertSame(29, Date::calculate($newYork)->numberDaysInMonth());
        $this->assertSame(0, Date::calculate($newYork)->numberDaysLeftInMonth());
    }

    public function testNumberDaysInMonthDefaultsToNow()
    {
        $now = Date::nowDefault();
        $expected = cal_days_in_month(CAL_GREGORIAN, intval($now->format('n')), intval($now->format('Y')));
        $this->assertSame($expected, Calculate::numberDaysInMonthByGivenValues());
        $this->assertSame(
            cal_days_in_month(CAL_GREGORIAN, 2, intval($now->format('Y'))),
            Calculate::numberDaysInMonthByGivenValues(2)
        );
        $this->assertSame(29, Calculate::numberDaysInMonthByGivenValues(2, 2024));
    }

    public function testNumberDaysLeftInMonthDefaultsMonthAndYearToNow()
    {
        $now = Date::nowDefault();
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, intval($now->format('n')), intval($now->format('Y')));
        $this->assertSame($daysInMonth - 1, Calculate::numberDaysLeftInMonthByGivenValues(1));
        $this->assertSame(0, Calculate::numberDaysLeftInMonthByGivenValues($daysInMonth));
        $this->assertSame(
            cal_days_in_month(CAL_GREGORIAN, 2, intval($now->format('Y'))) - 10,
            Calculate::numberDaysLeftInMonthByGivenValues(10, 2)
        );
    }

    public function testNumberDaysLeftInMonthDefaultDayIsToday()
    {
        $now = Date::nowDefault();
        $day = intval($now->format('j'));
        $month = intval($now->format('n'));
        if ($day === $month) {
            $this->markTestSkipped('Cannot distinguish day-of-month from month number today.');
        }
        $expected = 31 - $day;
        $actual = Calculate::numberDaysLeftInMonthByGivenValues(null, 1, 2020);
        $this->assertSame($expected, $actual);
    }

    /**
     * [first, second, timezone, expected hours, expected minutes, expected seconds]
     */
    public static function hoursMinutesSecondsProvider(): array
    {
        return [
            'same instant' => ['2024-05-05 10:00:00', '2024-05-05 10:00:00', 'UTC', 0, 0, 0],
            'spring DST calendar day' => ['2024-03-31 00:00:00', '2024-04-01 00:00:00', 'Europe/London', 23, 1380, 82800],
            'autumn DST calendar day' => ['2024-10-27 00:00:00', '2024-10-28 00:00:00', 'Europe/London', 25, 1500, 90000],
            'spring DST wall clock gap' => ['2024-03-31 00:30:00', '2024-03-31 02:30:00', 'Europe/London', 1, 60, 3600],
            'across leap day' => ['2024-02-28 12:00:00', '2024-03-01 12:00:00', 'UTC', 48, 2880, 172800],
            'across non leap Feb end' => ['2023-02-28 12:00:00', '2023-03-01 12:00:00', 'UTC', 24, 1440, 86400],
            'across the epoch' => ['1969-12-31 23:00:00', '1970-01-01 01:00:00', 'UTC', 2, 120, 7200],
        ];
    }

    #[DataProvider('hoursMinutesSecondsProvider')]
    public function testHoursMinutesSecondsBothWays(string $first, string $second, string $tz, int $hours, int $minutes, int $seconds)
    {
        $a = self::dt($first, $tz);
        $b = self::dt($second, $tz);
        foreach ([[$a, $b], [$b, $a]] as [$x, $y]) {
            $this->assertSame($hours, Date::calculate($x)->hours($y));
            $this->assertSame($minutes, Date::calculate($x)->minutes($y));
            $this->assertSame($seconds, Date::calculate($x)->seconds($y));
        }
    }

    public function testHoursMinutesSecondsAcrossTimezones()
    {
        $utc = self::dt('2024-06-01 12:00:00');
        $newYorkSameInstant = self::dt('2024-06-01 08:00:00', 'America/New_York');
        $this->assertSame(0, Date::calculate($utc)->hours($newYorkSameInstant));
        $this->assertSame(0, Date::calculate($utc)->minutes($newYorkSameInstant));
        $this->assertSame(0, Date::calculate($utc)->seconds($newYorkSameInstant));

        $tokyo = self::dt('2024-06-02 09:00:00', 'Asia/Tokyo'); // 2024-06-02 00:00 UTC
        $this->assertSame(12, Date::calculate($utc)->hours($tokyo));
        $this->assertSame(720, Date::calculate($tokyo)->minutes($utc));
        $this->assertSame(43200, Date::calculate($tokyo)->seconds($utc));
    }

    public function testCalculateDoesNotMutateTheOtherDate()
    {
        $a = self::dt('2024-03-31 00:00:00', 'Europe/London');
        $b = self::dt('2024-04-01 00:00:00', 'Europe/London');
        $before = $b->format('Y-m-d H:i:s.u e');
        Date::calculate($a)->hours($b);
        Date::calculate($a)->minutes($b);
        Date::calculate($a)->seconds($b);
        $this->assertSame($before, $b->format('Y-m-d H:i:s.u e'));
    }

    public function testComparisonsWithSameInstantInDifferentTimezones()
    {
        $utc = self::dt('2024-06-01 12:00:00');
        $newYork = self::dt('2024-06-01 08:00:00', 'America/New_York');

        $calc = Date::calculate($utc);
        $this->assertFalse($calc->isOlderThan($newYork));
        $this->assertFalse($calc->isNewerThan($newYork));
        $this->assertTrue($calc->isOlderThanOrEqualTo($newYork));
        $this->assertTrue($calc->isNewerThanOrEqualTo($newYork));
    }

    public function testComparisonsAcrossTimezonesUseTheInstant()
    {
        // 09:00 in London (BST, UTC+1) is 08:00 UTC, which is earlier than 08:30 UTC
        // even though the wall clock reads later.
        $london = self::dt('2024-06-01 09:00:00', 'Europe/London');
        $utc = self::dt('2024-06-01 08:30:00');

        $this->assertTrue(Date::calculate($london)->isOlderThan($utc));
        $this->assertTrue(Date::calculate($london)->isOlderThanOrEqualTo($utc));
        $this->assertFalse(Date::calculate($london)->isNewerThan($utc));
        $this->assertFalse(Date::calculate($london)->isNewerThanOrEqualTo($utc));

        $this->assertTrue(Date::calculate($utc)->isNewerThan($london));
        $this->assertTrue(Date::calculate($utc)->isNewerThanOrEqualTo($london));
        $this->assertFalse(Date::calculate($utc)->isOlderThan($london));
        $this->assertFalse(Date::calculate($utc)->isOlderThanOrEqualTo($london));
    }

    public function testComparisonsAtMicrosecondResolution()
    {
        $a = self::dt('2024-01-01 00:00:00.000001');
        $b = self::dt('2024-01-01 00:00:00.000002');
        $this->assertTrue(Date::calculate($a)->isOlderThan($b));
        $this->assertTrue(Date::calculate($b)->isNewerThan($a));
        $this->assertFalse(Date::calculate($b)->isOlderThanOrEqualTo($a));
        $this->assertFalse(Date::calculate($a)->isNewerThanOrEqualTo($b));
    }

    public static function invalidDaysLeftProvider(): array
    {
        return [
            'day 0' => [0, 1, 2024],
            'negative day' => [-1, 1, 2024],
            'day 32 in January' => [32, 1, 2024],
            'Feb 30 leap year' => [30, 2, 2024],
            'Feb 29 non leap year' => [29, 2, 2023],
            'month 0' => [1, 0, 2024],
            'month 13' => [1, 13, 2024],
        ];
    }

    #[DataProvider('invalidDaysLeftProvider')]
    public function testNumberDaysLeftInMonthRejectsInvalidValues(int $day, int $month, int $year)
    {
        $this->expectException(InvalidArgumentException::class);
        Calculate::numberDaysLeftInMonthByGivenValues($day, $month, $year);
    }

    public function testNumberDaysLeftInMonthBoundaries()
    {
        $this->assertSame(30, Calculate::numberDaysLeftInMonthByGivenValues(1, 1, 2024));
        $this->assertSame(0, Calculate::numberDaysLeftInMonthByGivenValues(31, 1, 2024));
        $this->assertSame(0, Calculate::numberDaysLeftInMonthByGivenValues(29, 2, 2024));
        $this->assertSame(0, Calculate::numberDaysLeftInMonthByGivenValues(28, 2, 2023));
    }
}
