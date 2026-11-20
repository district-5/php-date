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

namespace District5Tests\DateTests\TestFormatters;

use DateTime;
use District5\Date\Date;
use District5\Date\Formatters\InputFormatter;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use PHPUnit\Framework\TestCase;

/**
 * Class InputFormatterEdgeCaseTest
 * @package District5Tests\DateTests\TestFormatters
 */
class InputFormatterEdgeCaseTest extends TestCase
{
    public static function validYmdProvider(): array
    {
        return [
            'leap day in leap year' => ['2024-02-29', '-', 2024, 2, 29],
            'leap day in 400 year' => ['2000-02-29', '-', 2000, 2, 29],
            'first day of year' => ['2024-01-01', '-', 2024, 1, 1],
            'last day of year' => ['2023-12-31', '-', 2023, 12, 31],
            'slash separator' => ['2024/03/09', '/', 2024, 3, 9],
            'dot separator' => ['2024.03.09', '.', 2024, 3, 9],
            'empty separator' => ['20240309', '', 2024, 3, 9],
            'multi char separator' => ['2024--03--09', '--', 2024, 3, 9],
        ];
    }

    #[DataProvider('validYmdProvider')]
    public function testFromYmdValid(string $input, string $separator, int $y, int $m, int $d): void
    {
        $dt = Date::input($input)->fromYMD($separator);
        $this->assertInstanceOf(DateTime::class, $dt);
        $this->assertSame(sprintf('%04d-%02d-%02d', $y, $m, $d), $dt->format('Y-m-d'));
    }

    public static function invalidYmdProvider(): array
    {
        return [
            'feb 29 non leap year' => ['2023-02-29', '-'],
            'feb 29 in 1900 (century non leap)' => ['1900-02-29', '-'],
            'feb 30 leap year' => ['2024-02-30', '-'],
            'april 31' => ['2024-04-31', '-'],
            'month 13' => ['2024-13-01', '-'],
            'month 0' => ['2024-00-10', '-'],
            'day 0' => ['2024-01-00', '-'],
            'day 32' => ['2024-01-32', '-'],
            'leading whitespace' => [' 2024-01-01', '-'],
            'trailing whitespace' => ['2024-01-01 ', '-'],
            'trailing newline' => ["2024-01-01\n", '-'],
            'empty string' => ['', '-'],
            'two digit year' => ['24-01-01', '-'],
            'non padded month and day' => ['2024-1-1', '-'],
            'wrong separator' => ['2024/01/01', '-'],
            'separator expected but missing' => ['20240101', '-'],
            'five digit year' => ['02024-01-01', '-'],
            'signed year' => ['+2024-01-01', '-'],
            'garbage' => ['not-a-date', '-'],
            'with time' => ['2024-01-01 10:00:00', '-'],
            'dmy order given to ymd' => ['01-01-2024', '-'],
        ];
    }

    #[DataProvider('invalidYmdProvider')]
    public function testFromYmdInvalid(string $input, string $separator): void
    {
        $this->assertFalse(Date::input($input)->fromYMD($separator));
    }

    public static function validDmyMdyProvider(): array
    {
        return [
            'dmy leap day' => ['fromDMY', '29-02-2024', '-', '2024-02-29'],
            'dmy slash' => ['fromDMY', '09/03/2024', '/', '2024-03-09'],
            'dmy no separator' => ['fromDMY', '09032024', '', '2024-03-09'],
            'mdy leap day' => ['fromMDY', '02-29-2024', '-', '2024-02-29'],
            'mdy slash' => ['fromMDY', '03/09/2024', '/', '2024-03-09'],
            'mdy no separator' => ['fromMDY', '03092024', '', '2024-03-09'],
            'mdy dec 31' => ['fromMDY', '12-31-1999', '-', '1999-12-31'],
        ];
    }

    #[DataProvider('validDmyMdyProvider')]
    public function testFromDmyMdyValid(string $method, string $input, string $separator, string $expected): void
    {
        $dt = Date::input($input)->{$method}($separator);
        $this->assertInstanceOf(DateTime::class, $dt);
        $this->assertSame($expected, $dt->format('Y-m-d'));
    }

    public static function invalidDmyMdyProvider(): array
    {
        return [
            'dmy feb 29 non leap' => ['fromDMY', '29-02-2023', '-'],
            'dmy feb 30' => ['fromDMY', '30-02-2024', '-'],
            'dmy month 13' => ['fromDMY', '01-13-2024', '-'],
            'dmy day 0' => ['fromDMY', '00-01-2024', '-'],
            'dmy two digit year' => ['fromDMY', '01-01-24', '-'],
            'dmy wrong separator' => ['fromDMY', '01/01/2024', '-'],
            'dmy whitespace' => ['fromDMY', ' 01-01-2024', '-'],
            'dmy empty' => ['fromDMY', '', '-'],
            'mdy given dmy with day > 12' => ['fromMDY', '13-01-2024', '-'],
            'mdy feb 29 non leap' => ['fromMDY', '02-29-2023', '-'],
            'mdy day 0' => ['fromMDY', '01-00-2024', '-'],
            'mdy two digit year' => ['fromMDY', '01-01-24', '-'],
            'mdy wrong separator' => ['fromMDY', '01.01.2024', '-'],
            'mdy trailing whitespace' => ['fromMDY', '01-01-2024 ', '-'],
            'mdy empty' => ['fromMDY', '', '-'],
        ];
    }

    #[DataProvider('invalidDmyMdyProvider')]
    public function testFromDmyMdyInvalid(string $method, string $input, string $separator): void
    {
        $this->assertFalse(Date::input($input)->{$method}($separator));
    }

    public function testFromYmdHisValidWithCustomSeparators(): void
    {
        $dt = Date::input('2024/02/29 23.59.59')->fromYMDHIS('/', '.');
        $this->assertInstanceOf(DateTime::class, $dt);
        $this->assertSame('2024-02-29 23:59:59', $dt->format('Y-m-d H:i:s'));

        $dt = Date::input('2024-01-01 00:00:00')->fromYMDHIS();
        $this->assertSame('2024-01-01 00:00:00', $dt->format('Y-m-d H:i:s'));
    }

    public static function malformedYmdHisProvider(): array
    {
        return [
            'empty' => [''],
            'non padded time' => ['2024-01-01 1:2:3'],
            'leading whitespace' => [' 2024-01-01 10:00:00'],
            'trailing junk' => ['2024-01-01 10:00:00x'],
            'missing seconds' => ['2024-01-01 10:00'],
            'T separator' => ['2024-01-01T10:00:00'],
            'date only' => ['2024-01-01'],
        ];
    }

    #[DataProvider('malformedYmdHisProvider')]
    public function testFromYmdHisMalformed(string $input): void
    {
        $this->assertFalse(Date::input($input)->fromYMDHIS());
    }

    public static function overflowYmdHisProvider(): array
    {
        return [
            'feb 29 non leap' => ['2023-02-29 10:00:00'],
            'hour 24' => ['2024-01-01 24:00:00'],
            'hour 25' => ['2024-01-01 25:00:00'],
            'minute 60' => ['2024-01-01 10:60:00'],
            'second 60' => ['2024-01-01 10:00:60'],
        ];
    }

    #[DataProvider('overflowYmdHisProvider')]
    public function testFromYmdHisRejectsOverflowingValues(string $input): void
    {
        // PHP's createFromFormat() would roll these over (e.g. Feb 29 2023 -> Mar 1); fromYMDHIS() must reject them,
        // consistent with fromYMD()/fromDMY()/fromMDY().
        $this->assertFalse(Date::input($input)->fromYMDHIS());
    }

    public static function letterSeparatorProvider(): array
    {
        // Separators that are also DateTime format characters must be matched literally.
        return [
            'fromYMD with d' => ['fromYMD', '2024d03d09', 'd', '2024-03-09'],
            'fromYMD with Y' => ['fromYMD', '2024Y03Y09', 'Y', '2024-03-09'],
            'fromDMY with m' => ['fromDMY', '09m03m2024', 'm', '2024-03-09'],
            'fromMDY with backslash' => ['fromMDY', '03\\09\\2024', '\\', '2024-03-09'],
            'fromMDY with word' => ['fromMDY', '03at09at2024', 'at', '2024-03-09'],
        ];
    }

    #[DataProvider('letterSeparatorProvider')]
    public function testSeparatorsAreTreatedLiterally(string $method, string $input, string $separator, string $expected): void
    {
        $dt = Date::input($input)->{$method}($separator);
        $this->assertInstanceOf(DateTime::class, $dt);
        $this->assertSame($expected, $dt->format('Y-m-d'));
    }

    public function testYmdHisSeparatorsAreTreatedLiterally(): void
    {
        $dt = Date::input('2024T03T09 13h14h15')->fromYMDHIS('T', 'h');
        $this->assertInstanceOf(DateTime::class, $dt);
        $this->assertSame('2024-03-09 13:14:15', $dt->format('Y-m-d H:i:s'));
        // The letter must actually be present, not interpreted as a field.
        $this->assertFalse(Date::input('2024-03-09 13:14:15')->fromYMDHIS('T', 'h'));
    }

    public function testFromFormatIsNotStrictAndAllowsCallerControl(): void
    {
        // fromFormat() is documented as a thin wrapper around DateTime::createFromFormat.
        $dt = Date::input('2024-03-09 13:14:15')->fromFormat('Y-m-d H:i:s');
        $this->assertSame('2024-03-09 13:14:15', $dt->format('Y-m-d H:i:s'));

        // '!' resets unspecified fields to the epoch values, so time becomes midnight deterministically.
        $dt = Date::input('2024-03-09')->fromFormat('!Y-m-d');
        $this->assertSame('2024-03-09 00:00:00.000000', $dt->format('Y-m-d H:i:s.u'));

        $this->assertFalse(Date::input('abc')->fromFormat('Y-m-d'));
        $this->assertFalse(Date::input('')->fromFormat('Y-m-d'));
    }

    public function testFromFormatAcceptsIntegerInput(): void
    {
        $dt = (new InputFormatter(20240309))->fromFormat('!Ymd');
        $this->assertSame('2024-03-09', $dt->format('Y-m-d'));
    }

    public static function unixTimestampProvider(): array
    {
        return [
            'epoch' => ['0', '1970-01-01 00:00:00'],
            'one second before epoch' => ['-1', '1969-12-31 23:59:59'],
            'known value' => ['1700000000', '2023-11-14 22:13:20'],
            '32 bit overflow boundary' => ['2147483648', '2038-01-19 03:14:08'],
        ];
    }

    #[DataProvider('unixTimestampProvider')]
    public function testFromUnixTimestampValid(string $input, string $expectedUtc): void
    {
        $dt = Date::input($input)->fromUnixTimestamp();
        $this->assertInstanceOf(DateTime::class, $dt);
        $this->assertSame($expectedUtc, $dt->format('Y-m-d H:i:s'));
        $this->assertSame('+00:00', $dt->format('P'));
        $this->assertSame((int)$input, $dt->getTimestamp());

        $viaAlias = Date::input($input)->fromTimestamp();
        $this->assertEquals($dt, $viaAlias);
    }

    public function testFromUnixTimestampWithIntegerInput(): void
    {
        $dt = Date::input(1700000000)->fromUnixTimestamp();
        $this->assertSame('2023-11-14 22:13:20', $dt->format('Y-m-d H:i:s'));
    }

    public static function invalidUnixTimestampProvider(): array
    {
        return [
            'leading zero' => ['01700000000'],
            'decimal in strict mode' => ['1700000000.5'],
            'exponent' => ['1.5e9'],
            'leading space' => [' 1'],
            'trailing space' => ['1 '],
            'empty' => [''],
            'alpha' => ['abc'],
            'plus sign' => ['+1'],
        ];
    }

    #[DataProvider('invalidUnixTimestampProvider')]
    public function testFromUnixTimestampInvalid(string $input): void
    {
        $this->assertFalse(Date::input($input)->fromUnixTimestamp());
    }

    public function testFromUnixTimestampNonStrictDelegatesToMicroseconds(): void
    {
        $dt = Date::input('1700000000.123456')->fromUnixTimestamp(false);
        $this->assertInstanceOf(DateTime::class, $dt);
        $this->assertSame('2023-11-14 22:13:20.123456', $dt->format('Y-m-d H:i:s.u'));

        // Non-strict with a decimal still requires a full 6 digit microsecond part.
        $this->assertFalse(Date::input('1700000000.5')->fromUnixTimestamp(false));

        // Non-strict without a decimal behaves exactly as strict.
        $dt = Date::input('1700000000')->fromUnixTimestamp(false);
        $this->assertSame('2023-11-14 22:13:20.000000', $dt->format('Y-m-d H:i:s.u'));
    }

    public function testFromMicrosecondTimestamp(): void
    {
        $dt = Date::input('1700000000.000001')->fromMicrosecondTimestamp();
        $this->assertSame('2023-11-14 22:13:20.000001', $dt->format('Y-m-d H:i:s.u'));

        $dt = Date::input('-1.000000')->fromMicrosecondTimestamp();
        $this->assertSame('1969-12-31 23:59:59.000000', $dt->format('Y-m-d H:i:s.u'));

        // Negative values are signed decimals: -0.5s and -1.5s before the epoch.
        $dt = Date::input('-0.500000')->fromMicrosecondTimestamp();
        $this->assertSame('1969-12-31 23:59:59.500000', $dt->format('Y-m-d H:i:s.u'));
        $dt = Date::input('-1.500000')->fromMicrosecondTimestamp();
        $this->assertSame('1969-12-31 23:59:58.500000', $dt->format('Y-m-d H:i:s.u'));
        $dt = Date::input('-0.000001')->fromMicrosecondTimestamp();
        $this->assertSame('1969-12-31 23:59:59.999999', $dt->format('Y-m-d H:i:s.u'));
        // Non-canonical forms are rejected in the same way as leading zeros.
        $this->assertFalse(Date::input('-0.000000')->fromMicrosecondTimestamp());
        $this->assertFalse(Date::input('+1.000000')->fromMicrosecondTimestamp());

        // Strict: must have exactly 6 fractional digits.
        $this->assertFalse(Date::input('1700000000')->fromMicrosecondTimestamp());
        $this->assertFalse(Date::input('1700000000.123')->fromMicrosecondTimestamp());
        $this->assertFalse(Date::input('1700000000.1234567')->fromMicrosecondTimestamp());
        $this->assertFalse(Date::input('')->fromMicrosecondTimestamp());

        // Non-strict: whole number falls back to unix timestamp parsing.
        $dt = Date::input('1700000000')->fromMicrosecondTimestamp(false);
        $this->assertSame('2023-11-14 22:13:20.000000', $dt->format('Y-m-d H:i:s.u'));
        $this->assertFalse(Date::input('01700000000')->fromMicrosecondTimestamp(false));
        // Non-strict with decimal still enforces microsecond precision.
        $this->assertFalse(Date::input('1700000000.123')->fromMicrosecondTimestamp(false));
    }

    public function testFromMillisecondTimestamp(): void
    {
        $dt = Date::input('1700000000.001')->fromMillisecondTimestamp();
        $this->assertSame('2023-11-14 22:13:20.001000', $dt->format('Y-m-d H:i:s.u'));
        $this->assertSame(1, (int)$dt->format('v'));

        $dt = Date::input('1700000000.999')->fromMillisecondTimestamp();
        $this->assertSame('2023-11-14 22:13:20.999000', $dt->format('Y-m-d H:i:s.u'));

        $dt = Date::input('-0.500')->fromMillisecondTimestamp();
        $this->assertSame('1969-12-31 23:59:59.500000', $dt->format('Y-m-d H:i:s.u'));
        $dt = Date::input('-1.250')->fromMillisecondTimestamp();
        $this->assertSame('1969-12-31 23:59:58.750000', $dt->format('Y-m-d H:i:s.u'));

        // Strict: exactly 3 fractional digits.
        $this->assertFalse(Date::input('1700000000')->fromMillisecondTimestamp());
        $this->assertFalse(Date::input('1700000000.1')->fromMillisecondTimestamp());
        $this->assertFalse(Date::input('1700000000.123456')->fromMillisecondTimestamp());

        // Non-strict: whole number falls back to unix timestamp parsing.
        $dt = Date::input('1700000000')->fromMillisecondTimestamp(false);
        $this->assertSame('2023-11-14 22:13:20.000000', $dt->format('Y-m-d H:i:s.u'));
        $this->assertFalse(Date::input('abc')->fromMillisecondTimestamp(false));
        $this->assertFalse(Date::input('1700000000.1')->fromMillisecondTimestamp(false));
    }

    public function testFloatInputKeepsFullPrecision(): void
    {
        // strval() would truncate this to "1700000000.1235" (precision ini = 14).
        $dt = (new InputFormatter(1700000000.123456))->fromMicrosecondTimestamp();
        $this->assertInstanceOf(DateTime::class, $dt);
        $this->assertSame('2023-11-14 22:13:20.123456', $dt->format('Y-m-d H:i:s.u'));

        $dt = (new InputFormatter(1700000000.123456))->fromUnixTimestamp(false);
        $this->assertSame('2023-11-14 22:13:20.123456', $dt->format('Y-m-d H:i:s.u'));

        $dt = (new InputFormatter(1700000000.123))->fromMillisecondTimestamp();
        $this->assertSame('2023-11-14 22:13:20.123000', $dt->format('Y-m-d H:i:s.u'));

        $dt = (new InputFormatter(-0.5))->fromMicrosecondTimestamp();
        $this->assertSame('1969-12-31 23:59:59.500000', $dt->format('Y-m-d H:i:s.u'));

        // A whole-number float is a plain unix timestamp.
        $dt = (new InputFormatter(1700000000.0))->fromUnixTimestamp();
        $this->assertSame('2023-11-14 22:13:20.000000', $dt->format('Y-m-d H:i:s.u'));
        // A fractional float is not a whole-second timestamp in strict mode.
        $this->assertFalse((new InputFormatter(1700000000.5))->fromUnixTimestamp());
    }

    public static function iso8601Provider(): array
    {
        // [input, expected UTC instant (with microseconds), expected offset in seconds]
        return [
            'utc offset' => ['2020-01-01T00:00:00+00:00', '2020-01-01 00:00:00.000000', 0],
            'positive half hour offset' => ['2020-01-01T12:30:45+05:30', '2020-01-01 07:00:45.000000', 19800],
            'negative offset' => ['2020-06-15T23:59:59-08:00', '2020-06-16 07:59:59.000000', -28800],
            'Z suffix' => ['2020-01-01T00:00:00Z', '2020-01-01 00:00:00.000000', 0],
            'offset without colon' => ['2020-01-01T12:30:45-0800', '2020-01-01 20:30:45.000000', -28800],
            'positive offset without colon' => ['2020-01-01T12:30:45+0530', '2020-01-01 07:00:45.000000', 19800],
            'negative zero offset' => ['2020-01-01T12:00:00-00:00', '2020-01-01 12:00:00.000000', 0],
            'milliseconds with Z' => ['2020-01-01T12:00:00.123Z', '2020-01-01 12:00:00.123000', 0],
            'microseconds with offset' => ['2020-01-01T12:00:00.123456+01:00', '2020-01-01 11:00:00.123456', 3600],
            'single fraction digit' => ['2020-01-01T12:00:00.5Z', '2020-01-01 12:00:00.500000', 0],
            'nanoseconds truncated' => ['2020-01-01T12:00:00.123456789Z', '2020-01-01 12:00:00.123456', 0],
            'leap day' => ['2024-02-29T23:59:59+00:00', '2024-02-29 23:59:59.000000', 0],
        ];
    }

    #[DataProvider('iso8601Provider')]
    public function testFromIso8601Valid(string $input, string $expectedUtc, int $offset): void
    {
        $dt = Date::input($input)->fromISO8601();
        $this->assertInstanceOf(DateTime::class, $dt);
        $this->assertSame($offset, $dt->getOffset());
        $utc = (clone $dt)->setTimezone(new \DateTimeZone('UTC'));
        $this->assertSame($expectedUtc, $utc->format('Y-m-d H:i:s.u'));
    }

    public function testFromIso8601RoundTripsAtomOutput(): void
    {
        $input = '2020-06-15T23:59:59-08:00';
        $this->assertSame($input, Date::input($input)->fromISO8601()->format(DATE_ATOM));
    }

    public static function invalidIso8601Provider(): array
    {
        return [
            'invalid date' => ['2020-02-30T00:00:00+00:00'],
            'month 13' => ['2020-13-01T00:00:00Z'],
            'hour 24' => ['2020-01-01T24:00:00+00:00'],
            'minute 60' => ['2020-01-01T12:60:00Z'],
            'second 60' => ['2020-01-01T12:00:60Z'],
            'missing offset' => ['2020-01-01T12:30:45'],
            'space instead of T' => ['2020-01-01 12:30:45+00:00'],
            'lowercase z' => ['2020-01-01T12:30:45z'],
            'hour only offset' => ['2020-01-01T12:30:45+08'],
            'offset hour 24' => ['2020-01-01T12:30:45+24:00'],
            'offset minute 60' => ['2020-01-01T12:30:45+05:60'],
            'empty fraction' => ['2020-01-01T12:30:45.Z'],
            'too many fraction digits' => ['2020-01-01T12:30:45.1234567890Z'],
            'trailing newline' => ["2020-01-01T12:30:45Z\n"],
            'leading whitespace' => [' 2020-01-01T12:30:45Z'],
            'date only' => ['2020-01-01'],
            'garbage' => ['not a date'],
            'empty' => [''],
        ];
    }

    #[DataProvider('invalidIso8601Provider')]
    public function testFromIso8601Invalid(string $input): void
    {
        $this->assertFalse(Date::input($input)->fromISO8601());
    }

    #[RequiresPhpExtension('mongodb')]
    public function testFromMongoUtcDateTime(): void
    {
        $dt = Date::input('')->fromMongoUtcDateTime(new UTCDateTime(1700000000123));
        $this->assertInstanceOf(DateTime::class, $dt);
        $this->assertSame('2023-11-14 22:13:20.123000', $dt->format('Y-m-d H:i:s.u'));
        $this->assertSame(0, $dt->getOffset());
    }

    #[RequiresPhpExtension('mongodb')]
    public function testFromMongoObjectId(): void
    {
        // 0x65540080 === 1700003968
        $dt = Date::input('')->fromMongoObjectId(new ObjectId('655400800000000000000000'));
        $this->assertInstanceOf(DateTime::class, $dt);
        $this->assertSame(1700003968, $dt->getTimestamp());
        $this->assertSame('2023-11-14 23:19:28', $dt->format('Y-m-d H:i:s'));
    }
}
