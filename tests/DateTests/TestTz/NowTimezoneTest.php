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
use District5\Date\Tz\NowTimezone;
use District5\Date\Tz\TzConstants;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Class NowTimezoneTest
 * @package District5Tests\DateTests\TestTz
 */
class NowTimezoneTest extends TestCase
{
    /**
     * Maximum tolerated drift (in seconds) between a 'now' result and a reference DateTime.
     */
    private const TOLERANCE_SECONDS = 5;

    private string $originalTimezone;

    protected function setUp(): void
    {
        $this->originalTimezone = date_default_timezone_get();
    }

    protected function tearDown(): void
    {
        date_default_timezone_set($this->originalTimezone);
    }

    private function assertIsNowInZone(mixed $result, string $expectedZoneName): void
    {
        $this->assertInstanceOf(DateTime::class, $result);
        $this->assertSame($expectedZoneName, $result->getTimezone()->getName());
        $reference = new DateTime('now', new DateTimeZone($expectedZoneName));
        $this->assertSame($reference->getOffset(), $result->getOffset());
        $this->assertLessThanOrEqual(
            self::TOLERANCE_SECONDS,
            abs($reference->getTimestamp() - $result->getTimestamp())
        );
    }

    public static function zeroOffsetProvider(): array
    {
        return [
            ['0'],
            ['00'],
            ['0000'],
            ['00:00'],
            ['+0'],
            ['+00'],
            ['+0000'],
            ['+00:00'],
        ];
    }

    #[DataProvider('zeroOffsetProvider')]
    public function testZeroOffset(string $offset): void
    {
        $result = Date::now()->fromOffset($offset);
        $this->assertInstanceOf(DateTime::class, $result);
        $this->assertEquals(0, $result->getOffset());
        $this->assertSame('+00:00', $result->getTimezone()->getName());
        $this->assertLessThanOrEqual(self::TOLERANCE_SECONDS, abs(time() - $result->getTimestamp()));
    }

    public function testOffsetFromString()
    {
        $nowZeros = Date::now()->fromOffset('+0400');
        $this->assertEquals(14400, $nowZeros->getOffset());
    }

    public static function validOffsetProvider(): array
    {
        return [
            'four digit positive' => ['+0400', '+04:00', 14400],
            'four digit negative' => ['-0500', '-05:00', -18000],
            'three digit positive is zero padded' => ['+400', '+04:00', 14400],
            'three digit negative is zero padded' => ['-800', '-08:00', -28800],
            'kolkata half hour' => ['+0530', '+05:30', 19800],
            'kolkata half hour short' => ['+530', '+05:30', 19800],
            'kathmandu three quarter hour' => ['+0545', '+05:45', 20700],
            'negative three quarter hour' => ['-0545', '-05:45', -20700],
            'max western' => ['-1200', '-12:00', -43200],
            'max eastern' => ['+1400', '+14:00', 50400],
            'short colon form' => ['+5:30', '+05:30', 19800],
        ];
    }

    #[DataProvider('validOffsetProvider')]
    public function testValidOffsets(string $offset, string $expectedName, int $expectedSeconds): void
    {
        $result = Date::now()->fromOffset($offset);
        $this->assertInstanceOf(DateTime::class, $result);
        $this->assertSame($expectedName, $result->getTimezone()->getName());
        $this->assertSame($expectedSeconds, $result->getOffset());
        // The instant represented must still be 'now', regardless of the offset.
        $this->assertLessThanOrEqual(self::TOLERANCE_SECONDS, abs(time() - $result->getTimestamp()));
    }

    public static function invalidOffsetProvider(): array
    {
        return [
            'empty' => [''],
            'too short' => ['+1'],
            'too short two digits' => ['+01'],
            'too long' => ['+010000'],
            'long colon form' => ['+05:30'],
            'missing sign' => ['0530'],
            'missing sign three digits' => ['530'],
            'wrong sign character' => ['*0530'],
            'letters' => ['+abcdefg'],
            'short letters' => ['+ab0'],
            'out of range' => ['+9999'],
            'timezone identifier' => ['This/Is/Not/Valid'],
        ];
    }

    #[DataProvider('invalidOffsetProvider')]
    public function testInvalidOffsets(string $offset): void
    {
        $this->assertFalse(Date::now()->fromOffset($offset));
    }

    public function testOffsetInvalid()
    {
        $this->assertFalse(Date::now()->fromOffset('+abcdefg'));
    }

    public function testTimezoneString()
    {
        $nowZeros = Date::now()->inTimezone(TzConstants::EUROPE_LONDON);
        $this->assertEquals(TzConstants::EUROPE_LONDON, $nowZeros->getTimezone()->getName());
    }

    public static function namedTimezoneProvider(): array
    {
        return [
            [TzConstants::UTC],
            [TzConstants::EUROPE_LONDON],
            [TzConstants::AMERICA_NEW_YORK],
            [TzConstants::ASIA_KOLKATA],
            [TzConstants::ASIA_KATHMANDU],
            [TzConstants::AUSTRALIA_SYDNEY],
            [TzConstants::PACIFIC_CHATHAM],
        ];
    }

    #[DataProvider('namedTimezoneProvider')]
    public function testInTimezoneReturnsNowInThatZone(string $timezone): void
    {
        $this->assertIsNowInZone(Date::now()->inTimezone($timezone), $timezone);
    }

    public static function invalidTimezoneProvider(): array
    {
        return [
            'nonsense' => ['This/Is/Not/Valid'],
            'empty' => [''],
            'wrong case' => ['europe/london'],
            'lower case utc' => ['utc'],
            'offset string' => ['+05:30'],
            'abbreviation' => ['EST'],
            'trailing whitespace' => ['Europe/London '],
        ];
    }

    #[DataProvider('invalidTimezoneProvider')]
    public function testInTimezoneInvalid(string $timezone): void
    {
        $this->assertFalse(Date::now()->inTimezone($timezone));
    }

    public function testTimezoneInvalid()
    {
        $this->assertFalse(Date::now()->fromOffset('This/Is/Not/Valid'));
    }

    public function testUtc()
    {
        $dt = Date::now()->utc();
        $this->assertEquals(TzConstants::UTC, $dt->getTimezone()->getName());
        $this->assertIsNowInZone($dt, TzConstants::UTC);
        $this->assertSame(0, $dt->getOffset());
    }

    public function testUtcIsIndependentOfDefaultTimezone()
    {
        date_default_timezone_set(TzConstants::ASIA_KATHMANDU);
        $this->assertIsNowInZone(Date::now()->utc(), TzConstants::UTC);
    }

    public function testLondon()
    {
        $this->assertIsNowInZone(Date::now()->london(), TzConstants::EUROPE_LONDON);
    }

    public function testPacific()
    {
        $this->assertIsNowInZone(Date::now()->pacific(), TzConstants::AMERICA_LOS_ANGELES);
    }

    public function testEastern()
    {
        $this->assertIsNowInZone(Date::now()->eastern(), TzConstants::AMERICA_NEW_YORK);
    }

    public function testCentral()
    {
        $this->assertIsNowInZone(Date::now()->central(), TzConstants::AMERICA_CHICAGO);
    }

    public static function defaultTimezoneProvider(): array
    {
        return [
            ['UTC'],
            ['Europe/London'],
            ['America/New_York'],
            ['Asia/Kolkata'],
            ['Asia/Kathmandu'],
        ];
    }

    #[DataProvider('defaultTimezoneProvider')]
    public function testDefaultFollowsPhpDefaultTimezone(string $timezone): void
    {
        date_default_timezone_set($timezone);
        $result = (new NowTimezone())->default();
        $this->assertIsNowInZone($result, $timezone);
    }

    public function testEachCallReturnsANewInstance()
    {
        $now = Date::now();
        $this->assertNotSame($now->utc(), $now->utc());
        $this->assertNotSame($now->london(), $now->london());
    }
}
