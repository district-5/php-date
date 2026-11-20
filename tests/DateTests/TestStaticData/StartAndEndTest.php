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
use DateTimeZone;
use District5\Date\Date;
use District5\Date\StaticData\StartAndEnd;
use PHPUnit\Framework\Attributes\DataProvider;
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

    public function testStartOfYesterday()
    {
        $startAndEnd = Date::startAndEnd();
        $yesterday = (new DateTime())->modify('-1 day');
        $this->assertEquals(
            $yesterday->format('Y-m-d 00:00:00'),
            $startAndEnd->startOfYesterday()->format('Y-m-d H:i:s')
        );
    }

    public function testEndOfYesterday()
    {
        $startAndEnd = Date::startAndEnd();
        $yesterday = (new DateTime())->modify('-1 day');
        $this->assertEquals(
            $yesterday->format('Y-m-d 23:59:59'),
            $startAndEnd->endOfYesterday()->format('Y-m-d H:i:s')
        );
    }

    public function testStartOfTomorrow()
    {
        $startAndEnd = Date::startAndEnd();
        $tomorrow = (new DateTime())->modify('+1 day');
        $this->assertEquals(
            $tomorrow->format('Y-m-d 00:00:00'),
            $startAndEnd->startOfTomorrow()->format('Y-m-d H:i:s')
        );
    }

    public function testEndOfTomorrow()
    {
        $startAndEnd = Date::startAndEnd();
        $tomorrow = (new DateTime())->modify('+1 day');
        $this->assertEquals(
            $tomorrow->format('Y-m-d 23:59:59'),
            $startAndEnd->endOfTomorrow()->format('Y-m-d H:i:s')
        );
    }

    private const FORMAT = 'Y-m-d H:i:s.u';

    private string $originalTimezone;

    protected function setUp(): void
    {
        $this->originalTimezone = date_default_timezone_get();
    }

    protected function tearDown(): void
    {
        date_default_timezone_set($this->originalTimezone);
    }

    public static function dayBoundaryProvider(): array
    {
        return [
            'mid day with microseconds' => ['2024-05-17 13:14:15.161718', '2024-05-17'],
            'already midnight' => ['2024-05-17 00:00:00.000000', '2024-05-17'],
            'last microsecond of day' => ['2024-05-17 23:59:59.999999', '2024-05-17'],
            'leap day' => ['2024-02-29 12:00:00.000001', '2024-02-29'],
            'last day of year' => ['1999-12-31 18:00:00.000000', '1999-12-31'],
            'first day of year' => ['2000-01-01 00:00:00.000001', '2000-01-01'],
        ];
    }

    #[DataProvider('dayBoundaryProvider')]
    public function testStartAndEndOfDayFromDateTime(string $input, string $expectedDate): void
    {
        $dt = new DateTime($input);
        $start = Date::startAndEnd()->startOfDayFromDateTime($dt);
        $end = Date::startAndEnd()->endOfDayFromDateTime($dt);

        $this->assertSame($expectedDate . ' 00:00:00.000000', $start->format(self::FORMAT));
        $this->assertSame($expectedDate . ' 23:59:59.999999', $end->format(self::FORMAT));

        // Input is not mutated and new instances are returned.
        $this->assertSame($input, $dt->format(self::FORMAT));
        $this->assertNotSame($dt, $start);
        $this->assertNotSame($dt, $end);

        // The original instant always lies inside [start, end].
        $this->assertGreaterThanOrEqual($start, $dt);
        $this->assertLessThanOrEqual($end, $dt);
    }

    public static function dstDayProvider(): array
    {
        return [
            'london spring forward (23 hour day)' => ['Europe/London', '2024-03-31 12:00:00', 0, 3600, 23],
            'london fall back (25 hour day)' => ['Europe/London', '2024-10-27 12:00:00', 3600, 0, 25],
            'new york spring forward (23 hour day)' => ['America/New_York', '2024-03-10 12:00:00', -18000, -14400, 23],
            'new york fall back (25 hour day)' => ['America/New_York', '2024-11-03 12:00:00', -14400, -18000, 25],
            'kathmandu has no DST' => ['Asia/Kathmandu', '2024-03-31 12:00:00', 20700, 20700, 24],
        ];
    }

    #[DataProvider('dstDayProvider')]
    public function testStartAndEndOfDayAcrossDst(string $zone, string $input, int $startOffset, int $endOffset, int $hoursInDay): void
    {
        date_default_timezone_set($zone);
        $dt = new DateTime($input, new DateTimeZone($zone));
        $start = Date::startAndEnd()->startOfDayFromDateTime($dt);
        $end = Date::startAndEnd()->endOfDayFromDateTime($dt);

        $date = substr($input, 0, 10);
        $this->assertSame($date . ' 00:00:00.000000', $start->format(self::FORMAT));
        $this->assertSame($date . ' 23:59:59.999999', $end->format(self::FORMAT));
        $this->assertSame($zone, $start->getTimezone()->getName());
        $this->assertSame($zone, $end->getTimezone()->getName());
        $this->assertSame($startOffset, $start->getOffset());
        $this->assertSame($endOffset, $end->getOffset());
        // Elapsed seconds between the start and the end of the day reflect the DST change.
        $this->assertSame($hoursInDay * 3600 - 1, $end->getTimestamp() - $start->getTimestamp());
    }

    public static function monthBoundaryProvider(): array
    {
        return [
            'jan' => [2023, 1, '2023-01-31'],
            'feb non leap' => [2023, 2, '2023-02-28'],
            'feb leap' => [2024, 2, '2024-02-29'],
            'feb 1900 not leap' => [1900, 2, '1900-02-28'],
            'feb 2000 leap' => [2000, 2, '2000-02-29'],
            'apr 30 days' => [2024, 4, '2024-04-30'],
            'jun 30 days' => [2024, 6, '2024-06-30'],
            'sep 30 days' => [2024, 9, '2024-09-30'],
            'nov 30 days' => [2024, 11, '2024-11-30'],
            'dec 31 days' => [2024, 12, '2024-12-31'],
        ];
    }

    #[DataProvider('monthBoundaryProvider')]
    public function testStartAndEndOfMonthFromYearMonth(int $year, int $month, string $lastDay): void
    {
        $startAndEnd = Date::startAndEnd();
        $this->assertSame(
            sprintf('%04d-%02d-01 00:00:00.000000', $year, $month),
            $startAndEnd->startOfMonthFromYearMonth($year, $month)->format(self::FORMAT)
        );
        $this->assertSame(
            $lastDay . ' 23:59:59.999999',
            $startAndEnd->endOfMonthFromYearMonth($year, $month)->format(self::FORMAT)
        );
    }

    #[DataProvider('monthBoundaryProvider')]
    public function testStartAndEndOfMonthFromDateTime(int $year, int $month, string $lastDay): void
    {
        $startAndEnd = Date::startAndEnd();
        $inputs = [
            sprintf('%04d-%02d-01 00:00:00.000000', $year, $month),
            sprintf('%04d-%02d-15 08:09:10.111213', $year, $month),
            $lastDay . ' 23:59:59.999999',
        ];
        foreach ($inputs as $input) {
            $dt = new DateTime($input);
            $this->assertSame(
                sprintf('%04d-%02d-01 00:00:00.000000', $year, $month),
                $startAndEnd->startOfMonthFromDateTime($dt)->format(self::FORMAT),
                $input
            );
            $this->assertSame(
                $lastDay . ' 23:59:59.999999',
                $startAndEnd->endOfMonthFromDateTime($dt)->format(self::FORMAT),
                $input
            );
            $this->assertSame($input, $dt->format(self::FORMAT), 'input must not be mutated');
        }
    }

    public static function yearProvider(): array
    {
        return [
            [1900],
            [2000],
            [2023],
            [2024],
            [2100],
        ];
    }

    #[DataProvider('yearProvider')]
    public function testStartAndEndOfYear(int $year): void
    {
        $startAndEnd = Date::startAndEnd();
        $expectedStart = sprintf('%04d-01-01 00:00:00.000000', $year);
        $expectedEnd = sprintf('%04d-12-31 23:59:59.999999', $year);

        $this->assertSame($expectedStart, $startAndEnd->startOfYearFromYear($year)->format(self::FORMAT));
        $this->assertSame($expectedEnd, $startAndEnd->endOfYearFromYear($year)->format(self::FORMAT));

        foreach (['%04d-01-01 00:00:00.000000', '%04d-02-28 12:00:00.500000', '%04d-12-31 23:59:59.999999'] as $pattern) {
            $input = sprintf($pattern, $year);
            $dt = new DateTime($input);
            $this->assertSame($expectedStart, $startAndEnd->startOfYearFromDateTime($dt)->format(self::FORMAT), $input);
            $this->assertSame($expectedEnd, $startAndEnd->endOfYearFromDateTime($dt)->format(self::FORMAT), $input);
            $this->assertSame($input, $dt->format(self::FORMAT), 'input must not be mutated');
        }
    }

    public function testEndOfYearIsOneMicrosecondBeforeStartOfNextYear()
    {
        $startAndEnd = Date::startAndEnd();
        $end = $startAndEnd->endOfYearFromYear(2024);
        $next = $startAndEnd->startOfYearFromYear(2025);
        $this->assertSame(
            $next->format('Y-m-d H:i:s.u'),
            (clone $end)->modify('+1 microsecond')->format('Y-m-d H:i:s.u')
        );
    }

    public function testEndOfMonthIsOneMicrosecondBeforeStartOfNextMonth()
    {
        $startAndEnd = Date::startAndEnd();
        $end = $startAndEnd->endOfMonthFromYearMonth(2024, 2);
        $next = $startAndEnd->startOfMonthFromYearMonth(2024, 3);
        $this->assertSame(
            $next->format('Y-m-d H:i:s.u'),
            (clone $end)->modify('+1 microsecond')->format('Y-m-d H:i:s.u')
        );
    }

    public function testBoundariesUseDefaultTimezone()
    {
        date_default_timezone_set('Asia/Kolkata');
        $startAndEnd = new StartAndEnd();
        foreach ([
            $startAndEnd->startOfMonthFromYearMonth(2024, 2),
            $startAndEnd->endOfMonthFromYearMonth(2024, 2),
            $startAndEnd->startOfYearFromYear(2024),
            $startAndEnd->endOfYearFromYear(2024),
        ] as $dt) {
            $this->assertSame('Asia/Kolkata', $dt->getTimezone()->getName());
            $this->assertSame(19800, $dt->getOffset());
        }
    }

    /**
     * Run a 'relative to now' method, retrying if the day changed while it ran, so the test is deterministic.
     */
    private function assertRelativeDay(callable $method, string $modifier, string $time): void
    {
        $before = (new DateTime())->modify($modifier);
        $result = $method();
        $after = (new DateTime())->modify($modifier);
        $this->assertContains(
            $result->format(self::FORMAT),
            [$before->format('Y-m-d ' . $time), $after->format('Y-m-d ' . $time)]
        );
        $this->assertSame(date_default_timezone_get(), $result->getTimezone()->getName());
    }

    public static function relativeDayProvider(): array
    {
        return [
            'start of today' => ['startOfToday', '+0 days', '00:00:00.000000'],
            'end of today' => ['endOfToday', '+0 days', '23:59:59.999999'],
            'start of yesterday' => ['startOfYesterday', '-1 day', '00:00:00.000000'],
            'end of yesterday' => ['endOfYesterday', '-1 day', '23:59:59.999999'],
            'start of tomorrow' => ['startOfTomorrow', '+1 day', '00:00:00.000000'],
            'end of tomorrow' => ['endOfTomorrow', '+1 day', '23:59:59.999999'],
        ];
    }

    #[DataProvider('relativeDayProvider')]
    public function testRelativeDaysWithMicroseconds(string $method, string $modifier, string $time): void
    {
        $startAndEnd = Date::startAndEnd();
        $this->assertRelativeDay(fn () => $startAndEnd->$method(), $modifier, $time);
    }

    #[DataProvider('relativeDayProvider')]
    public function testRelativeDaysInNonUtcDefaultTimezone(string $method, string $modifier, string $time): void
    {
        date_default_timezone_set('Pacific/Kiritimati');
        $startAndEnd = Date::startAndEnd();
        $this->assertRelativeDay(fn () => $startAndEnd->$method(), $modifier, $time);
    }

    public static function dayFromYearMonthDayProvider(): array
    {
        return [
            'mid month' => [2024, 3, 15, '2024-03-15'],
            'leap day' => [2024, 2, 29, '2024-02-29'],
            'last day of year' => [2023, 12, 31, '2023-12-31'],
            'first day of month' => [2023, 6, 1, '2023-06-01'],
            '30 day month' => [2023, 4, 30, '2023-04-30'],
        ];
    }

    #[DataProvider('dayFromYearMonthDayProvider')]
    public function testStartOfDayFromYearMonthDay(int $year, int $month, int $day, string $expectedDate): void
    {
        $actual = Date::startAndEnd()->startOfDayFromYearMonthDay($year, $month, $day)->format(self::FORMAT);
        $expected = $expectedDate . ' 00:00:00.000000';
        $this->assertSame($expected, $actual);
    }

    #[DataProvider('dayFromYearMonthDayProvider')]
    public function testEndOfDayFromYearMonthDay(int $year, int $month, int $day, string $expectedDate): void
    {
        $actual = Date::startAndEnd()->endOfDayFromYearMonthDay($year, $month, $day)->format(self::FORMAT);
        $expected = $expectedDate . ' 23:59:59.999999';
        $this->assertSame($expected, $actual);
    }

    public static function foreignZoneProvider(): array
    {
        // [default zone, zone of the input DateTime]
        return [
            'tokyo input, new york default' => ['America/New_York', 'Asia/Tokyo'],
            'kathmandu input, utc default' => ['UTC', 'Asia/Kathmandu'],
            'new york input, london default' => ['Europe/London', 'America/New_York'],
            'offset input, utc default' => ['UTC', '+05:30'],
        ];
    }

    /**
     * Boundaries computed from a DateTime must be in the timezone of that DateTime, not the PHP default.
     */
    #[DataProvider('foreignZoneProvider')]
    public function testDayAndMonthBoundariesPreserveInputTimezone(string $defaultZone, string $inputZone): void
    {
        date_default_timezone_set($defaultZone);
        $input = new DateTime('2024-02-15 12:34:56.789000', new DateTimeZone($inputZone));
        $startAndEnd = Date::startAndEnd();

        $cases = [
            'startOfDayFromDateTime' => '2024-02-15 00:00:00.000000',
            'endOfDayFromDateTime' => '2024-02-15 23:59:59.999999',
            'startOfMonthFromDateTime' => '2024-02-01 00:00:00.000000',
            'endOfMonthFromDateTime' => '2024-02-29 23:59:59.999999',
        ];
        foreach ($cases as $method => $expected) {
            $result = $startAndEnd->$method($input);
            $this->assertSame($expected, $result->format(self::FORMAT), $method);
            $this->assertSame($inputZone, $result->getTimezone()->getName(), $method);
            // Same instant as building that wall clock time directly in the input zone.
            $this->assertSame(
                (new DateTime($expected, new DateTimeZone($inputZone)))->format('U.u'),
                $result->format('U.u'),
                $method
            );
        }
        $this->assertSame('2024-02-15 12:34:56.789000', $input->format(self::FORMAT));
        $this->assertSame($inputZone, $input->getTimezone()->getName());
    }
}
