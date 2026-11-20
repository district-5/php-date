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

namespace District5Tests\DateTests\TestTz;

use DateTime;
use DateTimeZone;
use District5\Date\Date;
use District5\Date\Tz\Timezone;
use District5\Date\Tz\TzConstants;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Class TimezoneTest
 * @package District5Tests\DateTests\TestTz
 */
class TimezoneTest extends TestCase
{
    public function testConvertToTimezone()
    {
        $dt = Date::now()->utc();

        $toLondon = Date::timezone($dt)->toTimezone(TzConstants::EUROPE_LONDON);
        $toLosAngeles = Date::timezone($toLondon)->toTimezone(TzConstants::AMERICA_LOS_ANGELES);
        $backToLondon = Date::timezone($toLosAngeles)->toTimezone(TzConstants::EUROPE_LONDON);

        $backToUtc = Date::timezone($backToLondon)->toTimezone(TzConstants::UTC);

        // Check first utc equals last utc
        $this->assertEquals($backToUtc->format('Y-m-d H:i:s'), $dt->format('Y-m-d H:i:s'));

        // Check first london equals last london
        $this->assertEquals($backToLondon->format('Y-m-d H:i:s'), $toLondon->format('Y-m-d H:i:s'));
    }

    public function testSwitchBetweenTimezones()
    {
        $dt = Date::now()->utc();

        $toLondon = Date::timezone($dt)->toTimezoneFromTimezone(
            TzConstants::EUROPE_LONDON,
            TzConstants::UTC
        );

        $toLosAngeles = Date::timezone($toLondon)->toTimezoneFromTimezone(
            TzConstants::AMERICA_LOS_ANGELES,
            TzConstants::EUROPE_LONDON
        );
        $backToLondon = Date::timezone($toLosAngeles)->toTimezoneFromTimezone(
            TzConstants::EUROPE_LONDON,
            TzConstants::AMERICA_LOS_ANGELES
        );

        $backToUtc = Date::timezone($backToLondon)->toTimezoneFromTimezone(
            TzConstants::UTC,
            TzConstants::EUROPE_LONDON
        );

        // Check first utc equals last utc
        $this->assertEquals($backToUtc->format('Y-m-d H:i:s'), $dt->format('Y-m-d H:i:s'));

        // Check first london equals last london
        $this->assertEquals($backToLondon->format('Y-m-d H:i:s'), $toLondon->format('Y-m-d H:i:s'));
    }

    public function testSwitchBetweenTimezonesWithoutDeclaration()
    {
        $dt = Date::now()->utc();

        $toLondon = Date::timezone($dt)->toTimezoneFromTimezone(
            TzConstants::EUROPE_LONDON,
            TzConstants::UTC
        );

        $toLosAngeles = Date::timezone($toLondon)->toTimezoneFromTimezone(
            TzConstants::AMERICA_LOS_ANGELES
        );
        $backToLondon = Date::timezone($toLosAngeles)->toTimezoneFromTimezone(
            TzConstants::EUROPE_LONDON
        );

        $backToUtc = Date::timezone($backToLondon)->toTimezoneFromTimezone(
            TzConstants::UTC
        );

        // Check first utc equals last utc
        $this->assertEquals($backToUtc->format('Y-m-d H:i:s'), $dt->format('Y-m-d H:i:s'));

        // Check first london equals last london
        $this->assertEquals($backToLondon->format('Y-m-d H:i:s'), $toLondon->format('Y-m-d H:i:s'));
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

    public static function toTimezoneProvider(): array
    {
        return [
            'utc to london in summer (BST)' => ['2024-07-01 12:00:00.123456', 'UTC', 'Europe/London', '2024-07-01 13:00:00.123456', 3600],
            'utc to london in winter (GMT)' => ['2024-01-15 12:00:00.000000', 'UTC', 'Europe/London', '2024-01-15 12:00:00.000000', 0],
            'utc to new york in summer (EDT)' => ['2024-07-01 12:00:00.000000', 'UTC', 'America/New_York', '2024-07-01 08:00:00.000000', -14400],
            'utc to new york in winter (EST)' => ['2024-01-15 12:00:00.000000', 'UTC', 'America/New_York', '2024-01-15 07:00:00.000000', -18000],
            'utc to kolkata (+05:30)' => ['2024-07-01 12:00:00.000000', 'UTC', 'Asia/Kolkata', '2024-07-01 17:30:00.000000', 19800],
            'utc to kathmandu (+05:45)' => ['2024-07-01 12:00:00.000000', 'UTC', 'Asia/Kathmandu', '2024-07-01 17:45:00.000000', 20700],
            'crosses year boundary forwards' => ['2024-12-31 23:30:00.000000', 'UTC', 'Asia/Kolkata', '2025-01-01 05:00:00.000000', 19800],
            'crosses year boundary backwards' => ['2025-01-01 00:30:00.000000', 'UTC', 'America/New_York', '2024-12-31 19:30:00.000000', -18000],
            'crosses leap day' => ['2024-02-29 22:00:00.000000', 'UTC', 'Asia/Kathmandu', '2024-03-01 03:45:00.000000', 20700],
            'london just before spring forward' => ['2024-03-31 00:59:59.999999', 'UTC', 'Europe/London', '2024-03-31 00:59:59.999999', 0],
            'london at spring forward' => ['2024-03-31 01:00:00.000000', 'UTC', 'Europe/London', '2024-03-31 02:00:00.000000', 3600],
            'kathmandu to new york' => ['2024-01-31 23:59:59.999999', 'Asia/Kathmandu', 'America/New_York', '2024-01-31 13:14:59.999999', -18000],
            'london to utc in summer' => ['2024-07-01 00:30:00.000000', 'Europe/London', 'UTC', '2024-06-30 23:30:00.000000', 0],
        ];
    }

    #[DataProvider('toTimezoneProvider')]
    public function testToTimezone(string $input, string $fromZone, string $toZone, string $expected, int $expectedOffset): void
    {
        $source = new DateTime($input, new DateTimeZone($fromZone));
        $result = Date::timezone($source)->toTimezone($toZone);

        $this->assertInstanceOf(DateTime::class, $result);
        $this->assertSame($expected, $result->format(self::FORMAT));
        $this->assertSame($toZone, $result->getTimezone()->getName());
        $this->assertSame($expectedOffset, $result->getOffset());
        // Converting zones must never change the instant in time.
        $this->assertSame($source->format('U.u'), $result->format('U.u'));
    }

    public function testToTimezoneDoesNotMutateInput()
    {
        $source = new DateTime('2024-07-01 12:00:00.500000', new DateTimeZone('UTC'));
        $timezone = Date::timezone($source);
        $result = $timezone->toTimezone('Asia/Kolkata');

        $this->assertNotSame($source, $result);
        $this->assertSame('2024-07-01 12:00:00.500000', $source->format(self::FORMAT));
        $this->assertSame('UTC', $source->getTimezone()->getName());

        // The Timezone instance itself must also be reusable (it holds an untouched clone).
        $second = $timezone->toTimezone('America/New_York');
        $this->assertSame('2024-07-01 08:00:00.500000', $second->format(self::FORMAT));
        $this->assertSame('2024-07-01 17:30:00.500000', $result->format(self::FORMAT));
        $this->assertNotSame($result, $second);
    }

    public function testTimezoneCapturesDateTimeAtConstruction()
    {
        $source = new DateTime('2024-07-01 12:00:00', new DateTimeZone('UTC'));
        $timezone = new Timezone($source);
        $source->modify('+1 day');
        $this->assertSame(
            '2024-07-01 13:00:00.000000',
            $timezone->toTimezone('Europe/London')->format(self::FORMAT)
        );
    }

    public static function invalidTimezoneProvider(): array
    {
        return [
            'nonsense' => ['Not/A_Zone'],
            'empty' => [''],
            'wrong case' => ['europe/london'],
            'offset string' => ['+05:30'],
        ];
    }

    #[DataProvider('invalidTimezoneProvider')]
    public function testToTimezoneInvalid(string $timezone): void
    {
        $source = new DateTime('2024-07-01 12:00:00', new DateTimeZone('UTC'));
        $this->assertFalse(Date::timezone($source)->toTimezone($timezone));
    }

    #[DataProvider('invalidTimezoneProvider')]
    public function testToTimezoneFromTimezoneInvalidTarget(string $timezone): void
    {
        $source = new DateTime('2024-07-01 12:00:00', new DateTimeZone('UTC'));
        $this->assertFalse(Date::timezone($source)->toTimezoneFromTimezone($timezone, 'UTC'));
        $this->assertFalse(Date::timezone($source)->toTimezoneFromTimezone($timezone));
    }

    public static function toTimezoneFromTimezoneProvider(): array
    {
        // [wall clock time, zone of the DateTime object, zone to interpret the wall clock in, target zone, expected]
        return [
            'new york summer wall clock to london' => ['2024-07-01 12:00:00.250000', 'UTC', 'America/New_York', 'Europe/London', '2024-07-01 17:00:00.250000'],
            'new york winter wall clock to london' => ['2024-01-15 12:00:00.000000', 'UTC', 'America/New_York', 'Europe/London', '2024-01-15 17:00:00.000000'],
            // US has moved to EDT (10 March) but UK is still on GMT (until 31 March) - only a 4 hour gap.
            'new york to london during DST mismatch' => ['2024-03-15 12:00:00.000000', 'UTC', 'America/New_York', 'Europe/London', '2024-03-15 16:00:00.000000'],
            'kolkata wall clock to utc' => ['2024-07-01 12:00:00.000000', 'UTC', 'Asia/Kolkata', 'UTC', '2024-07-01 06:30:00.000000'],
            'kathmandu wall clock to kolkata' => ['2024-07-01 00:10:00.000000', 'Europe/London', 'Asia/Kathmandu', 'Asia/Kolkata', '2024-06-30 23:55:00.000000'],
            'utc wall clock to kolkata crossing year' => ['2024-12-31 20:00:00.000000', 'America/New_York', 'UTC', 'Asia/Kolkata', '2025-01-01 01:30:00.000000'],
            'leap day wall clock preserved' => ['2024-02-29 12:00:00.000000', 'UTC', 'UTC', 'UTC', '2024-02-29 12:00:00.000000'],
        ];
    }

    #[DataProvider('toTimezoneFromTimezoneProvider')]
    public function testToTimezoneFromTimezone(string $wallClock, string $objectZone, string $fromZone, string $toZone, string $expected): void
    {
        $source = new DateTime($wallClock, new DateTimeZone($objectZone));
        $result = Date::timezone($source)->toTimezoneFromTimezone($toZone, $fromZone);

        $this->assertInstanceOf(DateTime::class, $result);
        $this->assertSame($expected, $result->format(self::FORMAT));
        $this->assertSame($toZone, $result->getTimezone()->getName());

        // Equivalent to building the wall clock time directly in the 'from' zone and converting.
        $reference = new DateTime($wallClock, new DateTimeZone($fromZone));
        $this->assertSame($reference->format('U.u'), $result->format('U.u'));

        // The input is untouched.
        $this->assertSame($wallClock, $source->format(self::FORMAT));
        $this->assertSame($objectZone, $source->getTimezone()->getName());
    }

    public function testToTimezoneFromTimezoneAcceptsDateTimeZoneObjects()
    {
        $source = new DateTime('2024-07-01 12:00:00', new DateTimeZone('UTC'));
        $result = Date::timezone($source)->toTimezoneFromTimezone(
            new DateTimeZone('Asia/Kolkata'),
            new DateTimeZone('America/New_York')
        );
        $this->assertSame('2024-07-01 21:30:00.000000', $result->format(self::FORMAT));
        $this->assertSame('Asia/Kolkata', $result->getTimezone()->getName());
    }

    public function testToTimezoneFromTimezoneWithoutFromUsesSourceZone()
    {
        $source = new DateTime('2024-01-31 23:59:59.999999', new DateTimeZone('Asia/Kathmandu'));
        $result = Date::timezone($source)->toTimezoneFromTimezone('UTC');
        $this->assertSame('2024-01-31 18:14:59.999999', $result->format(self::FORMAT));
        $this->assertSame('UTC', $result->getTimezone()->getName());
        // Without a 'from' zone this is equivalent to toTimezone().
        $this->assertSame(
            Date::timezone($source)->toTimezone('UTC')->format('U.u'),
            $result->format('U.u')
        );
    }

    public static function defaultTimezoneProvider(): array
    {
        return [
            ['UTC'],
            ['Europe/London'],
            ['Asia/Kathmandu'],
        ];
    }

    #[DataProvider('defaultTimezoneProvider')]
    public function testGetDefaultTimezone(string $timezone): void
    {
        date_default_timezone_set($timezone);
        $source = new DateTime('2024-07-01 12:00:00', new DateTimeZone('America/New_York'));
        $result = Date::timezone($source)->getDefaultTimezone();
        $this->assertInstanceOf(DateTimeZone::class, $result);
        // Reflects the PHP default, not the zone of the wrapped DateTime.
        $this->assertSame($timezone, $result->getName());
    }

    public static function dateTimeZoneObjectProvider(): array
    {
        return [
            'offset +05:30' => ['+05:30', '2024-07-01 17:30:00.000000', 19800],
            'offset -03:45' => ['-03:45', '2024-07-01 08:15:00.000000', -13500],
            'named zone in constants' => ['Asia/Kathmandu', '2024-07-01 17:45:00.000000', 20700],
            'abbreviation zone' => ['EST', '2024-07-01 07:00:00.000000', -18000],
        ];
    }

    #[DataProvider('dateTimeZoneObjectProvider')]
    public function testToTimezoneAcceptsAnyDateTimeZoneObject(string $zone, string $expected, int $offset): void
    {
        $source = new DateTime('2024-07-01 12:00:00', new DateTimeZone('UTC'));
        $tz = new DateTimeZone($zone);
        $result = Date::timezone($source)->toTimezone($tz);

        $this->assertInstanceOf(DateTime::class, $result);
        $this->assertSame($expected, $result->format(self::FORMAT));
        $this->assertSame($offset, $result->getOffset());
        $this->assertSame($source->format('U.u'), $result->format('U.u'));
        $this->assertSame($tz->getName(), $result->getTimezone()->getName());
    }

    #[DataProvider('dateTimeZoneObjectProvider')]
    public function testToTimezoneFromTimezoneAcceptsAnyDateTimeZoneObjectAsTarget(string $zone, string $expected, int $offset): void
    {
        $source = new DateTime('2024-07-01 12:00:00', new DateTimeZone('UTC'));
        $result = Date::timezone($source)->toTimezoneFromTimezone(new DateTimeZone($zone), 'UTC');
        $this->assertInstanceOf(DateTime::class, $result);
        $this->assertSame($expected, $result->format(self::FORMAT));
        $this->assertSame($offset, $result->getOffset());
    }

    public function testToTimezoneFromTimezoneWithOffsetFromZoneObject()
    {
        $source = new DateTime('2024-07-01 12:00:00.000500', new DateTimeZone('UTC'));
        $result = Date::timezone($source)->toTimezoneFromTimezone('UTC', new DateTimeZone('+05:30'));
        $this->assertSame('2024-07-01 06:30:00.000500', $result->format(self::FORMAT));
    }

    public function testToTimezoneFromTimezoneWithOffsetSourceAndNoFromZone()
    {
        $source = new DateTime('2024-07-01 12:00:00', new DateTimeZone('+02:00'));
        $result = Date::timezone($source)->toTimezoneFromTimezone('UTC');
        $this->assertInstanceOf(DateTime::class, $result);
        $this->assertSame('2024-07-01 10:00:00.000000', $result->format(self::FORMAT));
        $this->assertSame('UTC', $result->getTimezone()->getName());
        $this->assertSame('+02:00', $source->getTimezone()->getName());
    }

    public static function invalidFromTimezoneProvider(): array
    {
        return [
            'nonsense' => ['Bad/Zone'],
            'empty' => [''],
            'wrong case' => ['europe/london'],
            'offset string (strings must be TzConstants identifiers)' => ['+05:30'],
        ];
    }

    #[DataProvider('invalidFromTimezoneProvider')]
    public function testToTimezoneFromTimezoneInvalidFromReturnsFalse(string $from): void
    {
        $source = new DateTime('2024-07-01 12:00:00', new DateTimeZone('UTC'));
        $this->assertFalse(Date::timezone($source)->toTimezoneFromTimezone('UTC', $from));
        $this->assertFalse(Date::timezone($source)->toTimezoneFromTimezone(new DateTimeZone('UTC'), $from));
    }

    public function testToTimezoneDoesNotShareTheGivenDateTimeZoneInstance()
    {
        $source = new DateTime('2024-07-01 12:00:00', new DateTimeZone('UTC'));
        $tz = new DateTimeZone('Asia/Kolkata');
        $first = Date::timezone($source)->toTimezone($tz);
        $second = Date::timezone($source)->toTimezoneFromTimezone($tz);
        $this->assertSame('2024-07-01 17:30:00.000000', $first->format(self::FORMAT));
        $this->assertSame('2024-07-01 17:30:00.000000', $second->format(self::FORMAT));
    }
}
