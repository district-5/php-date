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

namespace District5Tests\DateTests\TestManipulators;

use DateTime;
use DateTimeZone;
use District5\Date\Date;
use District5\Date\Manipulators\Minus;
use District5\Date\Manipulators\MinusFluid;
use District5\Date\Manipulators\Plus;
use District5\Date\Manipulators\PlusFluid;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Class ModifyTest
 * @package District5Tests\DateTests\TestManipulators
 */
class ModifyTest extends TestCase
{
    public function testNormal()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 00:15:10');
        $this->assertEquals(
            '2010-01-01 00:15:10',
            Date::modify(
                $dt
            )->withString('+0 seconds')->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            '2010-01-01 00:15:05',
            Date::modify(
                $dt
            )->withString('-5 seconds')->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            '2010-01-01 00:15:20',
            Date::modify(
                $dt
            )->withString('+10 seconds')->format('Y-m-d H:i:s')
        );
        $this->assertFalse(
            Date::modify(
                $dt
            )->withString('this is not valid.')
        );
    }

    public function testNormalInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 00:15:10');
        Date::modify(
            $dt,
            false
        )->withString('+0 seconds');
        $this->assertEquals(
            '2010-01-01 00:15:10',
            $dt->format('Y-m-d H:i:s')
        );

        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 00:15:10');
        Date::modify(
            $dt,
            false
        )->withString('-5 seconds');
        $this->assertEquals(
            '2010-01-01 00:15:05',
            $dt->format('Y-m-d H:i:s')
        );

        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 00:15:10');
        Date::modify(
            $dt,
            false
        )->withString('+10 seconds');
        $this->assertEquals(
            '2010-01-01 00:15:20',
            $dt->format('Y-m-d H:i:s')
        );
    }

    public function testTimeChange()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 00:15:10');
        $this->assertEquals(
            '2010-01-01 01:15:10',
            Date::modify(
                $dt
            )->setHours(1)->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            '2010-01-01 00:25:10',
            Date::modify(
                $dt
            )->setMinutes(25)->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            '2010-01-01 00:15:59',
            Date::modify(
                $dt
            )->setSeconds(59)->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            '2010-01-01 00:15:10.000123',
            Date::modify(
                $dt
            )->setMicroseconds(123)->format('Y-m-d H:i:s.u')
        );
    }

    public function testTimeChangeInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 00:15:10');

        Date::modify($dt, false)->setHours(1);
        $this->assertEquals(
            '2010-01-01 01:15:10',
            $dt->format('Y-m-d H:i:s')
        );

        $dt = Date::modify($dt, false)->setMinutes(25);
        $this->assertEquals(
            '2010-01-01 01:25:10',
            $dt->format('Y-m-d H:i:s')
        );

        $dt = Date::modify($dt, false)->setSeconds(59);
        $this->assertEquals(
            '2010-01-01 01:25:59',
            $dt->format('Y-m-d H:i:s')
        );

        Date::modify($dt, false)->setMicroseconds(123);
        $this->assertEquals(
            '2010-01-01 01:25:59.000123',
            $dt->format('Y-m-d H:i:s.u')
        );
    }

    public function testChangeTime()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2020-10-02 06:03:01.000001');
        $converted = Date::modify($date)->setTime(5, 4, 3, 2);
        $this->assertEquals(
            '2020-10-02 05:04:03.000002',
            $converted->format('Y-m-d H:i:s.u')
        );
        $converted = Date::modify($date)->setTime(8, 3, 1, 0);
        $this->assertEquals(
            '2020-10-02 08:03:01.000000',
            $converted->format('Y-m-d H:i:s.u')
        );
    }

    public function testChangeDate()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2020-10-02 06:03:01.000001');
        $converted = Date::modify($date)->setDate(1, 2, 3010);
        $this->assertEquals(
            '3010-02-01 06:03:01.000001',
            $converted->format('Y-m-d H:i:s.u')
        );

        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2020-10-02 06:03:01.000001');
        $converted = Date::modify($date)->setDate(15, 12, 2020);
        $this->assertEquals(
            '2020-12-15 06:03:01.000001',
            $converted->format('Y-m-d H:i:s.u')
        );

        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2020-10-02 06:03:01.000001');
        $this->assertFalse(
            Date::modify($date)->setDate(29, 2, 2019)
        );
    }

    public function testChangeTimeInPlace()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2020-10-02 06:03:01.000001');
        Date::modify($date, false)->setTime(5, 4, 3, 2);
        $this->assertEquals(
            '2020-10-02 05:04:03.000002',
            $date->format('Y-m-d H:i:s.u')
        );

        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2020-10-02 06:03:01.000001');
        Date::modify($date, false)->setTime(8, 3, 1, 0);
        $this->assertEquals(
            '2020-10-02 08:03:01.000000',
            $date->format('Y-m-d H:i:s.u')
        );
    }

    public function testChangeDateInPlace()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2020-10-02 06:03:01.000001');
        Date::modify($date, false)->setDate(1, 2, 3010);
        $this->assertEquals(
            '3010-02-01 06:03:01.000001',
            $date->format('Y-m-d H:i:s.u')
        );

        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2020-10-02 06:03:01.000001');
        Date::modify($date, false)->setDate(15, 12, 2020);
        $this->assertEquals(
            '2020-12-15 06:03:01.000001',
            $date->format('Y-m-d H:i:s.u')
        );

        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2020-10-02 06:03:01.000001');
        $performed = Date::modify($date, false)->setDate(29, 2, 2019);
        $this->assertFalse(
            $performed
        );
    }

    public static function aliasProvider(): array
    {
        return [
            'plus' => ['plus', Plus::class, '2025-01-16'],
            'add' => ['add', Plus::class, '2025-01-16'],
            'minus' => ['minus', Minus::class, '2025-01-14'],
            'subtract' => ['subtract', Minus::class, '2025-01-14'],
            'sub' => ['sub', Minus::class, '2025-01-14'],
        ];
    }

    #[DataProvider('aliasProvider')]
    public function testManipulatorAliases(string $method, string $expectedClass, string $expected)
    {
        $dt = new DateTime('2025-01-15 00:00:00', new DateTimeZone('UTC'));
        $manipulator = Date::modify($dt)->{$method}();
        $this->assertInstanceOf($expectedClass, $manipulator);
        $this->assertEquals($expected, $manipulator->days(1)->format('Y-m-d'));
        $this->assertEquals('2025-01-15', $dt->format('Y-m-d'));

        // In place mode is propagated through the alias.
        $manipulator = Date::modify($dt, false)->{$method}();
        $this->assertSame($dt, $manipulator->days(1));
        $this->assertEquals($expected, $dt->format('Y-m-d'));
    }

    public static function fluidAliasProvider(): array
    {
        return [
            'plusFluid' => ['plusFluid', PlusFluid::class, '2025-01-16'],
            'addFluid' => ['addFluid', PlusFluid::class, '2025-01-16'],
            'minusFluid' => ['minusFluid', MinusFluid::class, '2025-01-14'],
            'subtractFluid' => ['subtractFluid', MinusFluid::class, '2025-01-14'],
            'subFluid' => ['subFluid', MinusFluid::class, '2025-01-14'],
        ];
    }

    #[DataProvider('fluidAliasProvider')]
    public function testFluidAliases(string $method, string $expectedClass, string $expected)
    {
        $dt = new DateTime('2025-01-15 00:00:00', new DateTimeZone('UTC'));
        $fluid = Date::modify($dt)->{$method}();
        $this->assertInstanceOf($expectedClass, $fluid);
        $this->assertSame($fluid, $fluid->days(1));
        $this->assertEquals($expected, $fluid->getDateTime()->format('Y-m-d'));
        $this->assertEquals('2025-01-15', $dt->format('Y-m-d'));
    }

    public static function withStringProvider(): array
    {
        return [
            'last day of next month from Jan 31' => ['2025-01-31 10:20:30', 'UTC', 'last day of next month', '2025-02-28 10:20:30 UTC'],
            'first monday of january' => ['2025-06-15 10:20:30', 'UTC', 'first monday of january 2026', '2026-01-05 00:00:00 UTC'],
            'next friday' => ['2025-06-15 10:20:30', 'UTC', 'next friday', '2025-06-20 00:00:00 UTC'],
            'midnight' => ['2025-06-15 10:20:30', 'UTC', 'midnight', '2025-06-15 00:00:00 UTC'],
            '+1 day over year boundary' => ['2025-12-31 12:00:00', 'UTC', '+1 day', '2026-01-01 12:00:00 UTC'],
            '+1 month overflows like native PHP' => ['2025-01-31 00:00:00', 'UTC', '+1 month', '2025-03-03 00:00:00 UTC'],
            '+1 day across spring DST' => ['2025-03-29 12:00:00', 'Europe/London', '+1 day', '2025-03-30 12:00:00 BST'],
            '+1 hour across spring DST' => ['2025-03-30 00:30:00', 'Europe/London', '+1 hour', '2025-03-30 02:30:00 BST'],
        ];
    }

    #[DataProvider('withStringProvider')]
    public function testWithString(string $input, string $timezone, string $modifier, string $expected)
    {
        $format = 'Y-m-d H:i:s T';
        $dt = new DateTime($input, new DateTimeZone($timezone));
        $before = $dt->format($format);

        $result = Date::modify($dt)->withString($modifier);
        $this->assertNotSame($dt, $result);
        $this->assertEquals($expected, $result->format($format));
        $this->assertEquals($before, $dt->format($format));

        $result = Date::modify($dt, false)->withString($modifier);
        $this->assertSame($dt, $result);
        $this->assertEquals($expected, $dt->format($format));
    }

    public function testWithInvalidStringInPlaceLeavesInputUntouched()
    {
        $dt = new DateTime('2025-01-31 10:20:30', new DateTimeZone('UTC'));
        $this->assertFalse(Date::modify($dt, false)->withString('not a valid modifier'));
        $this->assertEquals('2025-01-31 10:20:30', $dt->format('Y-m-d H:i:s'));
    }

    public static function timeSetterProvider(): array
    {
        return [
            'setHours single digit' => ['setHours', [7], '2025-06-15 07:05:09.000042'],
            'setHours zero' => ['setHours', [0], '2025-06-15 00:05:09.000042'],
            'setHours 23' => ['setHours', [23], '2025-06-15 23:05:09.000042'],
            'setMinutes single digit' => ['setMinutes', [3], '2025-06-15 10:03:09.000042'],
            'setMinutes 59' => ['setMinutes', [59], '2025-06-15 10:59:09.000042'],
            'setSeconds zero' => ['setSeconds', [0], '2025-06-15 10:05:00.000042'],
            'setSeconds 59' => ['setSeconds', [59], '2025-06-15 10:05:59.000042'],
            'setMicroseconds small' => ['setMicroseconds', [1], '2025-06-15 10:05:09.000001'],
            'setMicroseconds max' => ['setMicroseconds', [999999], '2025-06-15 10:05:09.999999'],
            'setMicroseconds zero' => ['setMicroseconds', [0], '2025-06-15 10:05:09.000000'],
            'setTime defaults seconds and micros' => ['setTime', [1, 2], '2025-06-15 01:02:00.000000'],
            'setTime all parts' => ['setTime', [23, 59, 59, 999999], '2025-06-15 23:59:59.999999'],
        ];
    }

    #[DataProvider('timeSetterProvider')]
    public function testTimeSettersCloneAndInPlaceAgree(string $method, array $args, string $expected)
    {
        $format = 'Y-m-d H:i:s.u';
        $dt = new DateTime('2025-06-15 10:05:09.000042', new DateTimeZone('UTC'));

        $cloned = Date::modify($dt)->{$method}(...$args);
        $this->assertInstanceOf(DateTime::class, $cloned);
        $this->assertNotSame($dt, $cloned);
        $this->assertEquals($expected, $cloned->format($format));
        $this->assertEquals('2025-06-15 10:05:09.000042', $dt->format($format), 'Input mutated in clone mode');

        $inPlace = Date::modify($dt, false)->{$method}(...$args);
        $this->assertSame($dt, $inPlace);
        $this->assertEquals($expected, $dt->format($format));
    }

    public static function timeSetterOutOfRangeProvider(): array
    {
        return [
            'setHours 24' => ['setHours', [24]],
            'setHours 25' => ['setHours', [25]],
            'setHours -1' => ['setHours', [-1]],
            'setMinutes 60' => ['setMinutes', [60]],
            'setMinutes -5' => ['setMinutes', [-5]],
            'setSeconds 60' => ['setSeconds', [60]],
            'setSeconds -1' => ['setSeconds', [-1]],
            'setMicroseconds 1000000' => ['setMicroseconds', [1000000]],
            'setMicroseconds -1' => ['setMicroseconds', [-1]],
            'setTime 24:00' => ['setTime', [24, 0]],
            'setTime minutes 60' => ['setTime', [0, 60]],
            'setTime seconds 60' => ['setTime', [0, 0, 60]],
            'setTime micros 1000000' => ['setTime', [0, 0, 0, 1000000]],
            'setTime negative hour' => ['setTime', [-1, 0]],
        ];
    }

    #[DataProvider('timeSetterOutOfRangeProvider')]
    public function testTimeSettersRejectOutOfRangeInBothModes(string $method, array $args)
    {
        $format = 'Y-m-d H:i:s.u';
        $dt = new DateTime('2025-06-15 10:05:09.000042', new DateTimeZone('UTC'));

        $this->assertFalse(Date::modify($dt)->{$method}(...$args));
        $this->assertEquals('2025-06-15 10:05:09.000042', $dt->format($format));

        $this->assertFalse(Date::modify($dt, false)->{$method}(...$args));
        $this->assertEquals('2025-06-15 10:05:09.000042', $dt->format($format));
    }

    public static function timeSetterTimezoneProvider(): array
    {
        return [
            'setHours' => ['setHours', [5], '2025-06-15 05:20:30 America/New_York'],
            'setMinutes' => ['setMinutes', [5], '2025-06-15 10:05:30 America/New_York'],
            'setSeconds' => ['setSeconds', [5], '2025-06-15 10:20:05 America/New_York'],
            'setMicroseconds' => ['setMicroseconds', [5], '2025-06-15 10:20:30 America/New_York'],
            'setTime' => ['setTime', [5, 6], '2025-06-15 05:06:00 America/New_York'],
        ];
    }

    #[DataProvider('timeSetterTimezoneProvider')]
    public function testTimeSettersPreserveTimezone(string $method, array $args, string $expected)
    {
        $format = 'Y-m-d H:i:s e';
        $dt = new DateTime('2025-06-15 10:20:30', new DateTimeZone('America/New_York'));

        $inPlace = Date::modify(clone $dt, false)->{$method}(...$args);
        $this->assertEquals($expected, $inPlace->format($format));

        $cloned = Date::modify($dt)->{$method}(...$args);
        $this->assertEquals($expected, $cloned->format($format));
        $this->assertEquals('2025-06-15 10:20:30 America/New_York', $dt->format($format));
    }

    public static function setDateProvider(): array
    {
        return [
            'all null keeps date' => [[null, null, null], '2024-01-31 06:03:01'],
            'day only' => [[15], '2024-01-15 06:03:01'],
            'month only keeps day when valid' => [[null, 3], '2024-03-31 06:03:01'],
            'year only' => [[null, null, 2030], '2030-01-31 06:03:01'],
            'leap day in leap year' => [[29, 2, 2024], '2024-02-29 06:03:01'],
            'leap day in century leap year' => [[29, 2, 2000], '2000-02-29 06:03:01'],
            'Dec 31' => [[31, 12, 2025], '2025-12-31 06:03:01'],
        ];
    }

    #[DataProvider('setDateProvider')]
    public function testSetDateValid(array $args, string $expected)
    {
        $dt = new DateTime('2024-01-31 06:03:01', new DateTimeZone('UTC'));

        $cloned = Date::modify($dt)->setDate(...$args);
        $this->assertNotSame($dt, $cloned);
        $this->assertEquals($expected, $cloned->format('Y-m-d H:i:s'));
        $this->assertEquals('2024-01-31 06:03:01', $dt->format('Y-m-d H:i:s'));

        $inPlace = Date::modify($dt, false)->setDate(...$args);
        $this->assertSame($dt, $inPlace);
        $this->assertEquals($expected, $dt->format('Y-m-d H:i:s'));
    }

    public static function setDateInvalidProvider(): array
    {
        return [
            'Feb 29 in non leap year' => [[29, 2, 2025]],
            'Feb 29 in non leap century' => [[29, 2, 1900]],
            'month only overflowing day (Jan 31 -> Feb)' => [[null, 2]],
            'Apr 31' => [[31, 4]],
            'day zero' => [[0]],
            'day 32' => [[32]],
            'month 13' => [[1, 13]],
            'month zero' => [[1, 0]],
        ];
    }

    #[DataProvider('setDateInvalidProvider')]
    public function testSetDateInvalidReturnsFalseWithoutMutatingInClone(array $args)
    {
        $dt = new DateTime('2025-01-31 06:03:01', new DateTimeZone('UTC'));
        $this->assertFalse(Date::modify($dt)->setDate(...$args));
        $this->assertEquals('2025-01-31 06:03:01', $dt->format('Y-m-d H:i:s'));
    }

    #[DataProvider('setDateInvalidProvider')]
    public function testSetDateInvalidInPlaceDoesNotMutateInput(array $args)
    {
        $dt = new DateTime('2025-01-31 06:03:01', new DateTimeZone('UTC'));
        $this->assertFalse(Date::modify($dt, false)->setDate(...$args));
        $this->assertEquals('2025-01-31 06:03:01', $dt->format('Y-m-d H:i:s'));
    }
}
