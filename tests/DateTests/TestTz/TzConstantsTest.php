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
use District5\Date\Date;
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
            // Only require PHP to accept the identifier. Some timezone databases (e.g. system tzdata on Ubuntu)
            // normalise renamed zones, so new DateTimeZone('Europe/Kiev')->getName() may return 'Europe/Kyiv'.
            try {
                new DateTimeZone($timezone);
            } catch (\Exception) {
                $invalid[] = $timezone;
            }
        }
        // Renamed/merged zones kept in TzConstants for backwards compatibility. Systems without legacy tzdata
        // (e.g. Ubuntu 24.04+ without the tzdata-legacy package) do not know these names at all.
        $legacy = [
            'America/Godthab', 'America/Nipigon', 'America/Pangnirtung', 'America/Rainy_River',
            'America/Santa_Isabel', 'America/Thunder_Bay', 'America/Yellowknife', 'Asia/Choibalsan',
            'Asia/Rangoon', 'Australia/Currie', 'Europe/Kiev', 'Europe/Uzhgorod', 'Europe/Zaporozhye',
            'Pacific/Enderbury', 'Pacific/Johnston',
        ];
        $this->assertSame([], array_values(array_diff($invalid, $legacy)));
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

    public function testCurrentNamesOfRenamedZonesAreListed()
    {
        $renamed = [
            'Europe/Kiev' => 'Europe/Kyiv',
            'America/Godthab' => 'America/Nuuk',
            'Asia/Rangoon' => 'Asia/Yangon',
            'Pacific/Enderbury' => 'Pacific/Kanton',
        ];
        foreach ($renamed as $old => $current) {
            $this->assertTrue(TzConstants::isValidTimezone($old), $old);
            $this->assertTrue(TzConstants::isValidTimezone($current), $current);
            $this->assertNotFalse(Date::now()->inTimezone($current), $current);
        }
        $this->assertSame('Europe/Kyiv', TzConstants::EUROPE_KYIV);
    }
}
