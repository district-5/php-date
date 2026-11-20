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

use DateTime;
use DateTimeZone;
use District5\Date\Date;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Class DateTimeValidatorBoundaryTest
 * @package District5Tests\DateTests\TestValidators
 */
class DateTimeValidatorBoundaryTest extends TestCase
{
    private static function make(string $value, string $tz = 'UTC'): DateTime
    {
        return DateTime::createFromFormat('Y-m-d H:i:s.u', $value, new DateTimeZone($tz));
    }

    public function testComparisonsWithEqualInstant(): void
    {
        $a = self::make('2024-01-01 12:00:00.000000');
        $b = self::make('2024-01-01 12:00:00.000000');
        $v = Date::validateObject($a);

        $this->assertFalse($v->isOlderThan($b));
        $this->assertFalse($v->isNewerThan($b));
        $this->assertTrue($v->isOlderThanOrEqualTo($b));
        $this->assertTrue($v->isNewerThanOrEqualTo($b));
    }

    public function testComparisonsWithSameInstantInDifferentTimezones(): void
    {
        $utc = self::make('2024-01-01 12:00:00.000000');
        $paris = self::make('2024-01-01 13:00:00.000000', 'Europe/Paris');
        $kathmandu = self::make('2024-01-01 17:45:00.000000', 'Asia/Kathmandu');
        $v = Date::validateObject($utc);

        foreach ([$paris, $kathmandu] as $other) {
            $this->assertFalse($v->isOlderThan($other));
            $this->assertFalse($v->isNewerThan($other));
            $this->assertTrue($v->isOlderThanOrEqualTo($other));
            $this->assertTrue($v->isNewerThanOrEqualTo($other));
            $this->assertTrue($v->isBetween($other, $other));
        }
    }

    public function testComparisonsWithDifferentTimezonesWhereWallClockMisleads(): void
    {
        // Wall clock 10:00 in New York (15:00 UTC) is newer than wall clock 14:00 in London (14:00 UTC).
        $newYork = self::make('2024-01-01 10:00:00.000000', 'America/New_York');
        $london = self::make('2024-01-01 14:00:00.000000', 'Europe/London');
        $this->assertTrue(Date::validateObject($newYork)->isNewerThan($london));
        $this->assertFalse(Date::validateObject($newYork)->isOlderThan($london));
        $this->assertTrue(Date::validateObject($london)->isOlderThan($newYork));
    }

    public function testComparisonsWithMicrosecondDifferences(): void
    {
        $earlier = self::make('2024-01-01 12:00:00.000000');
        $later = self::make('2024-01-01 12:00:00.000001');

        $this->assertTrue(Date::validateObject($earlier)->isOlderThan($later));
        $this->assertFalse(Date::validateObject($earlier)->isNewerThan($later));
        $this->assertTrue(Date::validateObject($later)->isNewerThan($earlier));
        $this->assertFalse(Date::validateObject($later)->isOlderThanOrEqualTo($earlier));
        $this->assertFalse(Date::validateObject($earlier)->isNewerThanOrEqualTo($later));
    }

    public static function betweenProvider(): array
    {
        return [
            'equal to start (inclusive)' => ['2024-01-01 00:00:00.000000', true],
            'equal to end (inclusive)' => ['2024-01-31 23:59:59.000000', true],
            'middle' => ['2024-01-15 12:00:00.000000', true],
            'one second before start' => ['2023-12-31 23:59:59.000000', false],
            'one second after end' => ['2024-02-01 00:00:00.000000', false],
        ];
    }

    #[DataProvider('betweenProvider')]
    public function testIsBetweenInclusiveBoundaries(string $value, bool $expected): void
    {
        $start = self::make('2024-01-01 00:00:00.000000');
        $end = self::make('2024-01-31 23:59:59.000000');
        $v = Date::validateObject(self::make($value));
        $this->assertSame($expected, $v->isBetween($start, $end));
        // Argument order does not matter.
        $this->assertSame($expected, $v->isBetween($end, $start));
    }

    public function testIsBetweenWithZeroWidthRange(): void
    {
        $point = self::make('2024-01-01 00:00:00.000000');
        $this->assertTrue(Date::validateObject($point)->isBetween($point, $point));
        $this->assertFalse(Date::validateObject(self::make('2024-01-01 00:00:01.000000'))->isBetween($point, $point));
    }

    public function testIsBetweenWithBoundsInOtherTimezones(): void
    {
        // Range is 09:00Z..17:00Z expressed in Tokyo and New York.
        $start = self::make('2024-01-01 18:00:00.000000', 'Asia/Tokyo');
        $end = self::make('2024-01-01 12:00:00.000000', 'America/New_York');
        $this->assertTrue(Date::validateObject(self::make('2024-01-01 09:00:00.000000'))->isBetween($start, $end));
        $this->assertTrue(Date::validateObject(self::make('2024-01-01 17:00:00.000000'))->isBetween($end, $start));
        $this->assertFalse(Date::validateObject(self::make('2024-01-01 17:00:01.000000'))->isBetween($start, $end));
    }

    public function testIsBetweenDoesNotModifyBounds(): void
    {
        $start = self::make('2024-01-01 00:00:00.000000');
        $end = self::make('2024-01-02 00:00:00.000000');
        Date::validateObject(self::make('2024-01-01 12:00:00.000000'))->isBetween($end, $start);
        $this->assertSame('2024-01-01 00:00:00', $start->format('Y-m-d H:i:s'));
        $this->assertSame('2024-01-02 00:00:00', $end->format('Y-m-d H:i:s'));
    }

    public static function subSecondBetweenProvider(): array
    {
        return [
            'middle' => ['2024-01-01 10:00:00.500000', true],
            'equal to start' => ['2024-01-01 10:00:00.100000', true],
            'equal to end' => ['2024-01-01 10:00:00.900000', true],
            'one microsecond before start' => ['2024-01-01 10:00:00.099999', false],
            'one microsecond after end' => ['2024-01-01 10:00:00.900001', false],
        ];
    }

    #[DataProvider('subSecondBetweenProvider')]
    public function testIsBetweenWithSubSecondBounds(string $value, bool $expected): void
    {
        $start = self::make('2024-01-01 10:00:00.100000');
        $end = self::make('2024-01-01 10:00:00.900000');
        $v = Date::validateObject(self::make($value));
        $this->assertSame($expected, $v->isBetween($start, $end));
        $this->assertSame($expected, $v->isBetween($end, $start));
    }

    public static function amPmProvider(): array
    {
        return [
            'midnight' => ['00:00:00.000000', true, 0],
            'last am second' => ['11:59:59.999999', true, 11],
            'noon' => ['12:00:00.000000', false, 12],
            'last pm second' => ['23:59:59.999999', false, 23],
        ];
    }

    #[DataProvider('amPmProvider')]
    public function testAmPmAndHourBoundaries(string $time, bool $isAm, int $hour): void
    {
        $v = Date::validateObject(self::make('2024-01-01 ' . $time));
        $this->assertSame($isAm, $v->isAM());
        $this->assertSame(!$isAm, $v->isPM());

        $this->assertFalse($v->isHourLessThan($hour));
        $this->assertTrue($v->isHourLessThan($hour + 1));
        $this->assertTrue($v->isHourLessThanOrEqualTo($hour));
        $this->assertFalse($v->isHourLessThanOrEqualTo($hour - 1));
        $this->assertFalse($v->isHourGreaterThan($hour));
        $this->assertTrue($v->isHourGreaterThan($hour - 1));
        $this->assertTrue($v->isHourGreaterThanOrEqualTo($hour));
        $this->assertFalse($v->isHourGreaterThanOrEqualTo($hour + 1));
    }

    public function testHourChecksUseTheDateTimesOwnTimezone(): void
    {
        $v = Date::validateObject(self::make('2024-01-01 08:00:00.000000', 'Asia/Tokyo')); // 23:00Z previous day
        $this->assertTrue($v->isAM());
        $this->assertTrue($v->isHourLessThan(9));
    }

    public static function leapYearProvider(): array
    {
        return [
            [1900, false], [2000, true], [2023, false], [2024, true], [2100, false], [2400, true],
        ];
    }

    #[DataProvider('leapYearProvider')]
    public function testIsLeapYear(int $year, bool $expected): void
    {
        $this->assertSame($expected, Date::validateObject(self::make(sprintf('%04d-06-01 00:00:00.000000', $year)))->isLeapYear());
    }

    public function testDayAndMonthNumberChecksWithOutOfRangeValues(): void
    {
        $v = Date::validateObject(self::make('2024-01-07 00:00:00.000000')); // Sunday
        $this->assertTrue($v->isDayOfWeekX(7));
        $this->assertFalse($v->isDayOfWeekX(0));
        $this->assertFalse($v->isDayOfWeekX(8));
        $this->assertTrue($v->isMonthNumberX(1));
        $this->assertFalse($v->isMonthNumberX(0));
        $this->assertFalse($v->isMonthNumberX(13));
        $this->assertTrue($v->isWeekend());
        $this->assertFalse($v->isWeekday());
    }

    public function testWeekendWeekdayBoundaries(): void
    {
        $friday = self::make('2024-01-05 23:59:59.999999');
        $saturday = self::make('2024-01-06 00:00:00.000000');
        $sunday = self::make('2024-01-07 23:59:59.999999');
        $monday = self::make('2024-01-08 00:00:00.000000');

        $this->assertTrue(Date::validateObject($friday)->isWeekday());
        $this->assertTrue(Date::validateObject($saturday)->isWeekend());
        $this->assertTrue(Date::validateObject($sunday)->isWeekend());
        $this->assertTrue(Date::validateObject($monday)->isWeekday());
    }

    public function testSameDayMonthYearBoundaries(): void
    {
        $start = self::make('2024-01-31 00:00:00.000000');
        $end = self::make('2024-01-31 23:59:59.999999');
        $next = self::make('2024-02-01 00:00:00.000000');
        $nextYear = self::make('2025-01-31 00:00:00.000000');

        $this->assertTrue(Date::validateObject($start)->isSameDay($end));
        $this->assertFalse(Date::validateObject($end)->isSameDay($next));
        $this->assertTrue(Date::validateObject($start)->isSameMonth($end));
        $this->assertFalse(Date::validateObject($end)->isSameMonth($next));
        // Same month number in a different year is not the same month.
        $this->assertFalse(Date::validateObject($start)->isSameMonth($nextYear));
        $this->assertFalse(Date::validateObject($start)->isSameDay($nextYear));
        $this->assertTrue(Date::validateObject($start)->isSameYear($next));
        $this->assertFalse(Date::validateObject($start)->isSameYear($nextYear));
        $this->assertFalse(Date::validateObject(self::make('2024-12-31 23:59:59.999999'))->isSameYear(self::make('2025-01-01 00:00:00.000000')));
    }

    public function testFutureAndPastRelativeToNow(): void
    {
        $this->assertTrue(Date::validateObject(self::make('1970-01-01 00:00:00.000000'))->isPast());
        $this->assertFalse(Date::validateObject(self::make('1970-01-01 00:00:00.000000'))->isFuture());
        $this->assertTrue(Date::validateObject(self::make('9999-12-31 23:59:59.000000'))->isFuture());
        $this->assertFalse(Date::validateObject(self::make('9999-12-31 23:59:59.000000'))->isPast());
    }

    public function testValidatorDoesNotTrackLaterMutationOfSource(): void
    {
        $dt = self::make('2024-01-01 00:00:00.000000');
        $v = Date::validateObject($dt);
        $dt->modify('+1 month');
        $this->assertTrue($v->isJanuary());
        $this->assertFalse($v->isFebruary());
    }
}
