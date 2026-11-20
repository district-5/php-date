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

namespace District5Tests\DateTests;

use DateTime;
use DateTimeZone;
use District5\Date\Calculators\Calculate;
use District5\Date\Calculators\Diff;
use District5\Date\Calculators\NtpServer;
use District5\Date\Converters\MongoConverter;
use District5\Date\Date;
use District5\Date\Formatters\InputFormatter;
use District5\Date\Formatters\OutputFormatter;
use District5\Date\Formatters\Sorter;
use District5\Date\Manipulators\Modify;
use District5\Date\StaticData\Month;
use District5\Date\StaticData\Recurring;
use District5\Date\StaticData\StartAndEnd;
use District5\Date\Tz\NowTimezone;
use District5\Date\Tz\Timezone;
use District5\Date\Validators\DateTimesValidator;
use District5\Date\Validators\DateTimeValidator;
use District5\Date\Validators\StringFormatValidator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Class DateFactoryTest
 * @package District5Tests\DateTests
 */
class DateFactoryTest extends TestCase
{
    private string $originalTimezone;

    protected function setUp(): void
    {
        $this->originalTimezone = date_default_timezone_get();
    }

    protected function tearDown(): void
    {
        date_default_timezone_set($this->originalTimezone);
    }

    /**
     * [factory method name, argument kind, expected class]
     * Argument kinds: 'none', 'dateTime', or a literal scalar/array argument.
     */
    public static function factoryProvider(): array
    {
        return [
            'output' => ['output', 'dateTime', OutputFormatter::class],
            'output (now)' => ['output', 'none', OutputFormatter::class],
            'diff' => ['diff', 'dateTime', Diff::class],
            'diff (now)' => ['diff', 'none', Diff::class],
            'calculate' => ['calculate', 'dateTime', Calculate::class],
            'calculate (now)' => ['calculate', 'none', Calculate::class],
            'modify' => ['modify', 'dateTime', Modify::class],
            'modify (now)' => ['modify', 'none', Modify::class],
            'input' => ['input', 'timestampString', InputFormatter::class],
            'recurring' => ['recurring', 'none', Recurring::class],
            'validateString' => ['validateString', 'dateString', StringFormatValidator::class],
            'validateArray' => ['validateArray', 'dateTimeArray', DateTimesValidator::class],
            'validateObject' => ['validateObject', 'dateTime', DateTimeValidator::class],
            'now' => ['now', 'none', NowTimezone::class],
            'timezone' => ['timezone', 'dateTime', Timezone::class],
            'timezone (now)' => ['timezone', 'none', Timezone::class],
            'sorter' => ['sorter', 'none', Sorter::class],
            'mongo' => ['mongo', 'none', MongoConverter::class],
            'month' => ['month', 'none', Month::class],
            'ntpServer' => ['ntpServer', 'none', NtpServer::class],
            'startAndEnd' => ['startAndEnd', 'none', StartAndEnd::class],
        ];
    }

    #[DataProvider('factoryProvider')]
    public function testFactoryReturnsExpectedType(string $method, string $argumentKind, string $expectedClass)
    {
        $dt = new DateTime('2024-02-29 12:00:00', new DateTimeZone('UTC'));
        $args = match ($argumentKind) {
            'none' => [],
            'dateTime' => [$dt],
            'dateTimeArray' => [[$dt]],
            'timestampString' => ['1709208000'],
            'dateString' => ['2024-02-29'],
        };
        $first = Date::$method(...$args);
        $this->assertInstanceOf($expectedClass, $first);
        // Each call creates a fresh instance
        $this->assertNotSame($first, Date::$method(...$args));
    }

    public function testFactoriesBindTheGivenDateTime()
    {
        $dt = new DateTime('2024-02-29 12:34:56', new DateTimeZone('Asia/Tokyo'));
        $this->assertSame('2024-02-29 12:34:56', Date::output($dt)->toFormat('Y-m-d H:i:s'));
        $this->assertSame(29, Date::calculate($dt)->numberDaysInMonth());
        $this->assertSame(0, Date::diff($dt)->seconds(clone $dt));
        $this->assertTrue(Date::validateObject($dt)->isNewerThanOrEqualTo(clone $dt));
    }

    public function testFactoriesWithoutDateTimeUseNow()
    {
        $now = Date::nowDefault();
        $this->assertLessThanOrEqual(1, Date::diff()->seconds($now));
        $this->assertSame($now->format('Y-m'), Date::output()->toFormat('Y-m'));
        $this->assertSame(
            cal_days_in_month(CAL_GREGORIAN, intval($now->format('n')), intval($now->format('Y'))),
            Date::calculate()->numberDaysInMonth()
        );
    }

    public function testModifyClonesByDefaultAndMutatesWhenAsked()
    {
        $dt = new DateTime('2024-02-29 12:00:00', new DateTimeZone('UTC'));
        $result = Date::modify($dt)->setDate(1, 3, 2024);
        $this->assertSame('2024-03-01', $result->format('Y-m-d'));
        $this->assertSame('2024-02-29', $dt->format('Y-m-d'));

        $result = Date::modify($dt, false)->setDate(1, 3, 2024);
        $this->assertSame($dt, $result);
        $this->assertSame('2024-03-01', $dt->format('Y-m-d'));
    }

    public function testCreateYMDHISMWithExplicitTimezone()
    {
        $dt = Date::createYMDHISM(2024, 2, 29, 13, 14, 15, 123456, 'Europe/London');
        $this->assertInstanceOf(DateTime::class, $dt);
        $this->assertSame('2024-02-29 13:14:15.123456', $dt->format('Y-m-d H:i:s.u'));
        $this->assertSame('Europe/London', $dt->getTimezone()->getName());
        $this->assertSame(1709212455, $dt->getTimestamp());

        $summer = Date::createYMDHISM(2024, 6, 1, 12, 0, 0, 0, 'Europe/London');
        $this->assertSame(1717239600, $summer->getTimestamp()); // BST, 11:00 UTC
    }

    public function testCreateYMDHISMDefaultsTimeToMidnightAndUsesDefaultTimezone()
    {
        date_default_timezone_set('America/New_York');
        $dt = Date::createYMDHISM(2024, 1, 15);
        $this->assertSame('2024-01-15 00:00:00.000000', $dt->format('Y-m-d H:i:s.u'));
        $this->assertSame('America/New_York', $dt->getTimezone()->getName());
        $this->assertSame(1705294800, $dt->getTimestamp());
    }

    public static function invalidYMDProvider(): array
    {
        return [
            'Feb 30' => [2024, 2, 30],
            'Feb 29 non leap year' => [2023, 2, 29],
            'Apr 31' => [2024, 4, 31],
            'month 13' => [2024, 13, 1],
            'day 0' => [2024, 1, 0],
        ];
    }

    #[DataProvider('invalidYMDProvider')]
    public function testCreateYMDHISMRejectsInvalidDates(int $year, int $month, int $day)
    {
        $this->assertFalse(Date::createYMDHISM($year, $month, $day, 0, 0, 0, 0, 'UTC'));
    }

    public function testCreateYMDHISMRejectsInvalidTimezone()
    {
        $this->assertFalse(Date::createYMDHISM(2024, 1, 1, 0, 0, 0, 0, 'Not/AZone'));
    }

    public function testCreateYMDHISMRejectsInvalidTime()
    {
        $result = Date::createYMDHISM(2024, 2, 29, 25, 0, 0, 0, 'UTC');
        $this->assertFalse($result);
    }

    public function testGetDefaultTimezoneFollowsPhpSetting()
    {
        date_default_timezone_set('Asia/Tokyo');
        $this->assertSame('Asia/Tokyo', Date::getDefaultTimezone());
        $this->assertSame('Asia/Tokyo', Date::nowDefault()->getTimezone()->getName());
        date_default_timezone_set('UTC');
        $this->assertSame('UTC', Date::getDefaultTimezone());
    }

    public function testNowUtcIsUtcRegardlessOfDefault()
    {
        date_default_timezone_set('Asia/Tokyo');
        $utc = Date::nowUtc();
        $this->assertSame('UTC', $utc->getTimezone()->getName());
        $this->assertEqualsWithDelta(time(), $utc->getTimestamp(), 1);
    }

    public function testFromStringIsInterpretedInDefaultTimezone()
    {
        date_default_timezone_set('Europe/London');
        $dt = Date::fromString('2024-06-01 12:00:00');
        $this->assertSame(1717239600, $dt->getTimestamp());
        // createFromFormat('U') always yields a UTC offset
        $this->assertSame('+00:00', $dt->getTimezone()->getName());
        $this->assertSame('2024-06-01 11:00:00', $dt->format('Y-m-d H:i:s'));

        date_default_timezone_set('UTC');
        $this->assertSame(1709208000, Date::fromString('2024-02-29 12:00:00')->getTimestamp());
        $this->assertSame(1709208000, Date::fromString('@1709208000')->getTimestamp());
        $this->assertFalse(Date::fromString(''));
    }

    public function testEpochIsTimestampZeroInAnyDefaultTimezone()
    {
        date_default_timezone_set('Europe/London');
        $epoch = Date::epoch();
        $this->assertSame(0, $epoch->getTimestamp());
    }

    public function testTimeAndMicrotime()
    {
        $before = time();
        $t = Date::time();
        $after = time();
        $this->assertGreaterThanOrEqual($before, $t);
        $this->assertLessThanOrEqual($after, $t);

        $float = Date::microtime(true);
        $this->assertIsFloat($float);
        $this->assertEqualsWithDelta(microtime(true), $float, 1.0);

        $string = Date::microtime();
        $this->assertIsString($string);
        $this->assertMatchesRegularExpression('/^0\.\d+ \d+$/', $string);
        [$fraction, $seconds] = explode(' ', $string);
        $this->assertEqualsWithDelta(time(), intval($seconds), 1);
        $this->assertLessThan(1, floatval($fraction));
    }

    /**
     * Builds a birth date relative to today, at midnight in the default timezone.
     */
    private static function bornYearsAgo(int $years, string $dayModifier): DateTime
    {
        $today = Date::nowDefault()->setTime(0, 0);
        $born = (clone $today)->modify($dayModifier);
        return $born->setDate(intval($born->format('Y')) - $years, intval($born->format('n')), intval($born->format('j')));
    }

    public function testAgeOnBirthdayAndAroundIt()
    {
        $today = Date::nowDefault();
        foreach ([$today, (clone $today)->modify('+1 day'), (clone $today)->modify('-1 day')] as $d) {
            if ($d->format('m-d') === '02-29') {
                $this->markTestSkipped('Birthday-relative age test is ambiguous around Feb 29.');
            }
        }

        $this->assertSame(30, Date::age(self::bornYearsAgo(30, '+0 days')), 'birthday today');
        $this->assertSame(30, Date::age(self::bornYearsAgo(30, '-1 day')), 'birthday yesterday');
        $this->assertSame(29, Date::age(self::bornYearsAgo(30, '+1 day')), 'birthday tomorrow');
        $this->assertSame(0, Date::age(Date::nowDefault()->setTime(0, 0)), 'born today');
    }

    public function testAgeMatchesDiffYearsAgainstNow()
    {
        $born = new DateTime('2000-02-29 00:00:00');
        $this->assertSame(Date::diff($born)->years(Date::nowDefault()), Date::age($born));
        $this->assertGreaterThanOrEqual(26, Date::age($born));
    }

    public function testCreateYMDHISMAcceptsAnyPhpTimezone()
    {
        $dt = Date::createYMDHISM(2024, 6, 1, 12, 0, 0, 0, 'Etc/GMT+5');
        $this->assertInstanceOf(DateTime::class, $dt);
        $this->assertSame('Etc/GMT+5', $dt->getTimezone()->getName());
        $this->assertSame('2024-06-01 12:00:00', $dt->format('Y-m-d H:i:s'));
        $this->assertSame(1717261200, $dt->getTimestamp());

        date_default_timezone_set('Etc/GMT+5');
        $default = Date::createYMDHISM(2024, 6, 1, 12);
        $this->assertInstanceOf(DateTime::class, $default);
        $this->assertSame(1717261200, $default->getTimestamp());
    }

    public static function invalidTimePartsProvider(): array
    {
        return [
            'hour 24' => [24, 0, 0, 0],
            'hour -1' => [-1, 0, 0, 0],
            'minute 60' => [0, 60, 0, 0],
            'second 60' => [0, 0, 60, 0],
            'microsecond 1000000' => [0, 0, 0, 1000000],
        ];
    }

    #[DataProvider('invalidTimePartsProvider')]
    public function testCreateYMDHISMRejectsOutOfRangeTimeParts(int $h, int $i, int $s, int $u)
    {
        $this->assertFalse(Date::createYMDHISM(2024, 1, 1, $h, $i, $s, $u, 'UTC'));
    }

    public function testEpochIsUtc()
    {
        date_default_timezone_set('Asia/Tokyo');
        $epoch = Date::epoch();
        $this->assertSame(0, $epoch->getTimestamp());
        $this->assertSame('UTC', $epoch->getTimezone()->getName());
        $this->assertSame('1970-01-01 00:00:00.000000', $epoch->format('Y-m-d H:i:s.u'));
    }
}
