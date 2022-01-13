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

use District5\Date\Date;
use District5\Date\Tz\TzConstants;
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
}
