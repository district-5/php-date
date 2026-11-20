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
use District5\Date\StaticData\Month;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

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

    public static function daysInMonthProvider(): array
    {
        return [
            'jan' => [1, 2023, 31],
            'feb non leap' => [2, 2023, 28],
            'mar' => [3, 2023, 31],
            'apr' => [4, 2023, 30],
            'may' => [5, 2023, 31],
            'jun' => [6, 2023, 30],
            'jul' => [7, 2023, 31],
            'aug' => [8, 2023, 31],
            'sep' => [9, 2023, 30],
            'oct' => [10, 2023, 31],
            'nov' => [11, 2023, 30],
            'dec' => [12, 2023, 31],
            'feb leap (divisible by 4)' => [2, 2024, 29],
            'feb century non leap (1900)' => [2, 1900, 28],
            'feb century leap (2000)' => [2, 2000, 29],
            'feb century non leap (2100)' => [2, 2100, 28],
            'feb leap (2400)' => [2, 2400, 29],
            'mar in leap year unaffected' => [3, 2024, 31],
        ];
    }

    #[DataProvider('daysInMonthProvider')]
    public function testNumberDaysInMonthForEveryMonth(int $month, int $year, int $expected): void
    {
        $this->assertSame($expected, Date::month()->numberDaysInMonth($month, $year));
    }

    #[DataProvider('daysInMonthProvider')]
    public function testLastDateInMonthForEveryMonth(int $month, int $year, int $expected): void
    {
        $result = Date::month()->lastDateInMonth($month, $year);
        $this->assertSame(
            sprintf('%04d-%02d-%02d 00:00:00.000000', $year, $month, $expected),
            $result->format(self::FORMAT)
        );
    }

    #[DataProvider('daysInMonthProvider')]
    public function testFirstDateInMonthForEveryMonth(int $month, int $year, int $expected): void
    {
        $result = Date::month()->firstDateInMonth($month, $year);
        $this->assertSame(
            sprintf('%04d-%02d-01 00:00:00.000000', $year, $month),
            $result->format(self::FORMAT)
        );
    }

    #[DataProvider('daysInMonthProvider')]
    public function testNumberDaysInMonthFromDateTimeForEveryMonth(int $month, int $year, int $expected): void
    {
        // Use the last moment of the month, to make sure nothing rolls over.
        $dt = new DateTime(sprintf('%04d-%02d-%02d 23:59:59.999999', $year, $month, $expected));
        $this->assertSame($expected, Date::month()->numberDaysInMonthFromDateTime($dt));
        $this->assertSame(
            sprintf('%04d-%02d-%02d', $year, $month, $expected),
            Date::month()->lastDateInMonthFromDateTime($dt)->format('Y-m-d')
        );
    }

    public function testFirstDateInMonthUsesDefaultTimezone()
    {
        date_default_timezone_set('Asia/Kathmandu');
        $result = Date::month()->firstDateInMonth(3, 2024);
        $this->assertSame('2024-03-01 00:00:00.000000', $result->format(self::FORMAT));
        $this->assertSame('Asia/Kathmandu', $result->getTimezone()->getName());
        $this->assertSame(20700, $result->getOffset());
    }

    public function testLastDateInMonthAcrossDstChange()
    {
        // Clocks go forward in London on the last Sunday in March.
        date_default_timezone_set('Europe/London');
        $result = Date::month()->lastDateInMonth(3, 2024);
        $this->assertSame('2024-03-31 00:00:00.000000', $result->format(self::FORMAT));
        $this->assertSame('Europe/London', $result->getTimezone()->getName());
        $this->assertSame(0, $result->getOffset());
        $this->assertSame(31, Date::month()->numberDaysInMonth(3, 2024));
        $this->assertSame(31, Date::month()->numberDaysInMonth(10, 2024));
    }

    public function testFirstDateInMonthFromDateTimeResetsTimeAndDoesNotMutate()
    {
        $input = new DateTime('2024-02-29 15:45:30.123456');
        $result = Date::month()->firstDateInMonthFromDateTime($input);

        $this->assertSame('2024-02-01 00:00:00.000000', $result->format(self::FORMAT));
        $this->assertNotSame($input, $result);
        $this->assertSame('2024-02-29 15:45:30.123456', $input->format(self::FORMAT));
    }

    public function testFirstDateInMonthFromDateTimeOnFirstAtMidnightIsUnchanged()
    {
        $input = new DateTime('2023-12-01 00:00:00.000000');
        $result = Date::month()->firstDateInMonthFromDateTime($input);
        $this->assertSame('2023-12-01 00:00:00.000000', $result->format(self::FORMAT));
        $this->assertEquals($input, $result);
    }

    public function testFirstDateInMonthFromDateTimeInNonUtcDefaultZone()
    {
        date_default_timezone_set('Asia/Kolkata');
        $input = new DateTime('2024-07-20 03:15:00', new DateTimeZone('Asia/Kolkata'));
        $result = Date::month()->firstDateInMonthFromDateTime($input);
        $this->assertSame('2024-07-01 00:00:00.000000', $result->format(self::FORMAT));
        $this->assertSame('Asia/Kolkata', $result->getTimezone()->getName());
    }

    public function testLastDateInMonthFromDateTimeDoesNotMutate()
    {
        $input = new DateTime('2023-02-10 10:00:00.000001');
        $result = Date::month()->lastDateInMonthFromDateTime($input);
        $this->assertSame('2023-02-28 00:00:00.000000', $result->format(self::FORMAT));
        $this->assertSame('2023-02-10 10:00:00.000001', $input->format(self::FORMAT));
    }

    public function testFirstAndLastDefaultToCurrentYear()
    {
        $before = intval(date('Y'));
        $first = Date::month()->firstDateInMonth(6);
        $last = Date::month()->lastDateInMonth(6);
        $days = Date::month()->numberDaysInMonth(2);
        $after = intval(date('Y'));

        $this->assertContains(intval($first->format('Y')), [$before, $after]);
        $this->assertSame('06-01 00:00:00.000000', $first->format('m-d H:i:s.u'));
        $this->assertContains(intval($last->format('Y')), [$before, $after]);
        $this->assertSame('06-30', $last->format('m-d'));
        $this->assertContains($days, [28, 29]);
        $isLeap = static fn (int $y): bool => ($y % 4 === 0 && $y % 100 !== 0) || $y % 400 === 0;
        $this->assertTrue(
            $days === ($isLeap($before) ? 29 : 28) || $days === ($isLeap($after) ? 29 : 28)
        );
    }

    public function testFirstDateInCurrentMonth()
    {
        date_default_timezone_set('Asia/Kathmandu');
        $before = new DateTime();
        $result = (new Month())->firstDateInCurrentMonth();
        $after = new DateTime();

        $this->assertContains(
            $result->format(self::FORMAT),
            [$before->format('Y-m-01 00:00:00.000000'), $after->format('Y-m-01 00:00:00.000000')]
        );
        $this->assertSame('Asia/Kathmandu', $result->getTimezone()->getName());
    }

    public static function invalidMonthProvider(): array
    {
        return [
            'zero' => [0],
            'thirteen' => [13],
            'negative' => [-1],
        ];
    }

    public static function invalidMonthMethodProvider(): array
    {
        $cases = [];
        foreach (['firstDateInMonth', 'lastDateInMonth', 'numberDaysInMonth'] as $method) {
            foreach (['zero' => 0, 'thirteen' => 13, 'negative' => -1, 'large' => 100] as $label => $month) {
                $cases[$method . ' ' . $label] = [$method, $month];
            }
        }
        return $cases;
    }

    /**
     * An invalid month must be rejected with a clear exception rather than rolling over into an adjacent month/year.
     */
    #[DataProvider('invalidMonthMethodProvider')]
    public function testInvalidMonthThrowsInvalidArgumentException(string $method, int $month): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Month number must be between 1 and 12, %d given.', $month));
        Date::month()->$method($month, 2024);
    }

    #[DataProvider('invalidMonthMethodProvider')]
    public function testInvalidMonthThrowsWithDefaultYear(string $method, int $month): void
    {
        $this->expectException(InvalidArgumentException::class);
        Date::month()->$method($month);
    }

    public static function currentMonthTimezoneProvider(): array
    {
        return [['UTC'], ['Europe/London'], ['America/New_York'], ['Asia/Kathmandu']];
    }

    #[DataProvider('currentMonthTimezoneProvider')]
    public function testLastDateInCurrentMonth(string $timezone): void
    {
        date_default_timezone_set($timezone);
        $before = new DateTime();
        $result = Date::month()->lastDateInCurrentMonth();
        $after = new DateTime();

        // 't' is the number of days in the month, so this is the last day of the current month.
        $this->assertContains(
            $result->format(self::FORMAT),
            [$before->format('Y-m-t 23:59:59.999999'), $after->format('Y-m-t 23:59:59.999999')]
        );
        $this->assertSame($timezone, $result->getTimezone()->getName());
        $this->assertSame(
            $result->format('Y-m-d'),
            Date::month()->lastDateInMonth(intval($result->format('n')), intval($result->format('Y')))->format('Y-m-d')
        );
    }

    public function testFirstDateInMonthFromDateTimePreservesInputTimezone()
    {
        date_default_timezone_set('America/New_York');
        $input = new DateTime('2024-03-01 05:00:00', new DateTimeZone('Asia/Tokyo'));
        $result = Date::month()->firstDateInMonthFromDateTime($input);
        $this->assertSame('2024-03-01 00:00:00.000000', $result->format(self::FORMAT));
        $this->assertSame('Asia/Tokyo', $result->getTimezone()->getName());
        $this->assertSame(32400, $result->getOffset());
    }
}
