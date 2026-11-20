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

use DateTimeZone;
use District5\Date\Tz\TzConstants;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Class TzConstantsTest
 * @package District5Tests\DateTests\TestTz
 */
class TzConstantsTest extends TestCase
{
    public function testEveryListedTimezoneIsKnownToPhp()
    {
        $this->assertNotEmpty(TzConstants::$ALL_TIMEZONES);
        $invalid = [];
        foreach (TzConstants::$ALL_TIMEZONES as $timezone) {
            try {
                $tz = new DateTimeZone($timezone);
                if ($tz->getName() !== $timezone) {
                    $invalid[] = $timezone;
                }
            } catch (\Exception) {
                $invalid[] = $timezone;
            }
        }
        $this->assertSame([], $invalid);
    }

    public function testAllTimezonesHasNoDuplicates()
    {
        $this->assertSame(
            count(TzConstants::$ALL_TIMEZONES),
            count(array_unique(TzConstants::$ALL_TIMEZONES))
        );
    }

    public function testEveryConstantIsListedInAllTimezones()
    {
        $constants = (new ReflectionClass(TzConstants::class))->getConstants();
        $this->assertNotEmpty($constants);
        $this->assertSame([], array_values(array_diff($constants, TzConstants::$ALL_TIMEZONES)));
        $this->assertSame([], array_values(array_diff(TzConstants::$ALL_TIMEZONES, $constants)));
    }

    public function testEveryListedTimezoneIsValid()
    {
        foreach (TzConstants::$ALL_TIMEZONES as $timezone) {
            $this->assertTrue(TzConstants::isValidTimezone($timezone), $timezone);
        }
    }

    public static function validTimezoneProvider(): array
    {
        return [
            [TzConstants::UTC],
            ['Europe/London'],
            ['America/New_York'],
            ['America/Los_Angeles'],
            ['America/Chicago'],
            ['Asia/Kolkata'],
            ['Asia/Kathmandu'],
            ['Australia/Sydney'],
        ];
    }

    #[DataProvider('validTimezoneProvider')]
    public function testIsValidTimezone(string $timezone): void
    {
        $this->assertTrue(TzConstants::isValidTimezone($timezone));
    }

    public static function invalidTimezoneProvider(): array
    {
        return [
            'empty' => [''],
            'nonsense' => ['This/Is/Not/Valid'],
            'lower case' => ['europe/london'],
            'upper case' => ['EUROPE/LONDON'],
            'lower case utc' => ['utc'],
            'leading whitespace' => [' UTC'],
            'trailing whitespace' => ['UTC '],
            'offset' => ['+00:00'],
            'abbreviation' => ['GMT'],
            'numeric string' => ['0'],
            'constant name rather than value' => ['EUROPE_LONDON'],
        ];
    }

    #[DataProvider('invalidTimezoneProvider')]
    public function testIsNotValidTimezone(string $timezone): void
    {
        $this->assertFalse(TzConstants::isValidTimezone($timezone));
    }
}
