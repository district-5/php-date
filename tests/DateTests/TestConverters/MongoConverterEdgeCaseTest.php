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

namespace District5Tests\DateTests\TestConverters;

use DateTime;
use DateTimeZone;
use District5\Date\Date;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Class MongoConverterEdgeCaseTest
 * @package District5Tests\DateTests\TestConverters
 */
class MongoConverterEdgeCaseTest extends TestCase
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

    private function requireUtcDateTime(): void
    {
        if (class_exists('\MongoDB\BSON\UTCDateTime') === false) {
            $this->markTestSkipped('\MongoDB\BSON\UTCDateTime class is not available. Skipping test.');
        }
    }

    private function requireObjectId(): void
    {
        if (class_exists('\MongoDB\BSON\ObjectId') === false) {
            $this->markTestSkipped('\MongoDB\BSON\ObjectId class is not available. Skipping test.');
        }
    }

    /**
     * [local date string with microseconds, timezone, expected milliseconds since epoch]
     */
    public static function utcDateTimeProvider(): array
    {
        return [
            'epoch' => ['1970-01-01 00:00:00.000000', 'UTC', '0'],
            'one millisecond after epoch' => ['1970-01-01 00:00:00.001000', 'UTC', '1'],
            'microseconds are truncated not rounded' => ['2024-06-01 12:00:00.123999', 'UTC', '1717243200123'],
            'last millisecond of a second' => ['2024-06-01 12:00:00.999000', 'UTC', '1717243200999'],
            'London BST is converted to UTC' => ['2024-06-01 12:00:00.123456', 'Europe/London', '1717239600123'],
            'London GMT' => ['2024-01-15 12:00:00.500000', 'Europe/London', '1705320000500'],
            'New York' => ['2024-06-01 08:00:00.250000', 'America/New_York', '1717243200250'],
            'Tokyo' => ['2024-06-01 21:00:00.750000', 'Asia/Tokyo', '1717243200750'],
            'leap day' => ['2024-02-29 23:59:59.999000', 'UTC', '1709251199999'],
            'far future' => ['2100-01-01 00:00:00.001000', 'UTC', '4102444800001'],
        ];
    }

    #[DataProvider('utcDateTimeProvider')]
    public function testConvertToAndFromUtcDateTime(string $local, string $tz, string $expectedMillis)
    {
        $this->requireUtcDateTime();
        $dt = DateTime::createFromFormat('Y-m-d H:i:s.u', $local, new DateTimeZone($tz));

        $mongo = Date::mongo()->convertTo($dt);
        $this->assertInstanceOf('\MongoDB\BSON\UTCDateTime', $mongo);
        $this->assertSame($expectedMillis, (string)$mongo);

        $back = Date::mongo()->convertFrom($mongo);
        $this->assertInstanceOf(DateTime::class, $back);
        // The driver always hands back UTC
        $this->assertSame('+00:00', $back->getTimezone()->getName());
        $this->assertSame($dt->format('U'), $back->format('U'));
        $this->assertSame($dt->format('v'), $back->format('v'));
        // Precision below a millisecond is lost
        $this->assertSame($dt->format('v') . '000', $back->format('u'));
    }

    public function testEveryMillisecondRoundTripsExactly()
    {
        $this->requireUtcDateTime();
        $utc = new DateTimeZone('UTC');
        $base = 1717243200;
        for ($ms = 0; $ms < 1000; $ms++) {
            $dt = DateTime::createFromFormat('U.u', sprintf('%d.%03d000', $base, $ms), $utc);
            $mongo = Date::mongo()->convertTo($dt);
            $this->assertSame(sprintf('%d%03d', $base, $ms), (string)$mongo, 'ms ' . $ms);
            $this->assertSame($dt->format('U.v'), Date::mongo()->convertFrom($mongo)->format('U.v'));
        }
    }

    public function testConvertToDoesNotDependOnDefaultTimezone()
    {
        $this->requireUtcDateTime();
        $dt = new DateTime('2024-06-01 12:00:00.123', new DateTimeZone('Europe/London'));
        date_default_timezone_set('UTC');
        $utcDefault = (string)Date::mongo()->convertTo($dt);
        date_default_timezone_set('Asia/Tokyo');
        $tokyoDefault = (string)Date::mongo()->convertTo($dt);
        $this->assertSame('1717239600123', $utcDefault);
        $this->assertSame($utcDefault, $tokyoDefault);
    }

    public function testConvertFromDriverCreatedValue()
    {
        $this->requireUtcDateTime();
        $cl = '\MongoDB\BSON\UTCDateTime';
        $back = Date::mongo()->convertFrom(new $cl(1610543228632));
        $this->assertSame('2021-01-13 13:07:08.632', $back->format('Y-m-d H:i:s.v'));
        $this->assertSame('+00:00', $back->getTimezone()->getName());
    }

    public function testConvertToBeforeEpoch()
    {
        $this->requireUtcDateTime();
        $dt = DateTime::createFromFormat('Y-m-d H:i:s.u', '1969-12-31 23:59:59.500000', new DateTimeZone('UTC'));
        $actual = (string)Date::mongo()->convertTo($dt);
        $this->assertSame('-500', $actual);
    }

    public function testConvertToWholeSecondsBeforeEpoch()
    {
        $this->requireUtcDateTime();
        $dt = new DateTime('1960-01-01 00:00:00', new DateTimeZone('UTC'));
        $mongo = Date::mongo()->convertTo($dt);
        $this->assertSame('-315619200000', (string)$mongo);
        $this->assertSame('1960-01-01 00:00:00.000', Date::mongo()->convertFrom($mongo)->format('Y-m-d H:i:s.v'));
    }

    /**
     * [UTC date string, expected 8 char hex timestamp prefix]
     */
    public static function objectIdProvider(): array
    {
        return [
            'one day after epoch is zero padded' => ['1970-01-02 00:00:00', '00015180'],
            'a 2021 date' => ['2021-01-13 13:07:08', '5ffef07c'],
            'leap day' => ['2024-02-29 12:00:00', '65e071c0'],
            'max 32-bit signed second' => ['2038-01-19 03:14:07', '7fffffff'],
        ];
    }

    #[DataProvider('objectIdProvider')]
    public function testToObjectIdEncodesTimestampInFirstFourBytes(string $utc, string $expectedHex)
    {
        $this->requireObjectId();
        date_default_timezone_set('UTC');
        $dt = new DateTime($utc, new DateTimeZone('UTC'));

        $objectId = Date::mongo()->toObjectId($dt);
        $this->assertInstanceOf('\MongoDB\BSON\ObjectId', $objectId);
        $this->assertSame($expectedHex . '0000000000000000', (string)$objectId);
        $this->assertSame($dt->getTimestamp(), $objectId->getTimestamp());

        $back = Date::mongo()->fromObjectId($objectId);
        $this->assertSame($dt->getTimestamp(), $back->getTimestamp());
        $this->assertSame('+00:00', $back->getTimezone()->getName());
    }

    public function testToObjectIdDropsSubSecondPrecision()
    {
        $this->requireObjectId();
        date_default_timezone_set('UTC');
        $dt = new DateTime('2024-06-01 12:00:00.999999', new DateTimeZone('UTC'));
        $back = Date::mongo()->fromObjectId(Date::mongo()->toObjectId($dt));
        $this->assertSame('2024-06-01 12:00:00.000000', $back->format('Y-m-d H:i:s.u'));
    }

    public function testFromObjectIdWithDriverGeneratedId()
    {
        $this->requireObjectId();
        $cl = '\MongoDB\BSON\ObjectId';
        // Well known example id from the MongoDB docs; first 4 bytes 0x507f1f77 = 1350508407
        $back = Date::mongo()->fromObjectId(new $cl('507f1f77bcf86cd799439011'));
        $this->assertSame(1350508407, $back->getTimestamp());
        $this->assertSame('2012-10-17 21:13:27', $back->format('Y-m-d H:i:s'));
        $this->assertSame('+00:00', $back->getTimezone()->getName());
    }

    public function testToObjectIdUsesTheInstantNotTheWallClock()
    {
        $this->requireObjectId();
        date_default_timezone_set('UTC');
        $london = new DateTime('2024-06-01 12:00:00', new DateTimeZone('Europe/London')); // 11:00 UTC
        $actual = Date::mongo()->toObjectId($london)->getTimestamp();
        $this->assertSame($london->getTimestamp(), $actual);
    }

    public function testToObjectIdRejectsTimestampsOutsideObjectIdRange()
    {
        if (class_exists('\\MongoDB\\BSON\\ObjectId') === false) {
            $this->markTestSkipped('\\MongoDB\\BSON\\ObjectId class is not available. Skipping test.');
        }
        $this->assertFalse(Date::mongo()->toObjectId(new DateTime('1969-12-31 23:59:59', new DateTimeZone('UTC'))));
        $this->assertFalse(Date::mongo()->toObjectId(new DateTime('@4294967296')));
        $this->assertSame(
            'ffffffff',
            substr((string)Date::mongo()->toObjectId(new DateTime('@4294967295')), 0, 8)
        );
    }

    public function testConvertToHasNoFloatRoundingError()
    {
        $this->requireUtcDateTime();
        foreach (['4315351328440', '1700000000001', '-1', '-999', '253402300799999'] as $ms) {
            $dt = (new \MongoDB\BSON\UTCDateTime(intval($ms)))->toDateTime();
            $this->assertSame($ms, (string)Date::mongo()->convertTo($dt), $ms);
        }
    }
}
