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
use District5\Date\Manipulators\PlusFluid;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Class PlusFluidTest
 * @package District5Tests\DateTests\TestManipulators
 */
class PlusFluidTest extends TestCase
{
    public function testPlusFluidAllOptions()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s u', '2010-01-01 00:15:09 000000');
        $modified = Date::modify($dt)->plusFluid()->millennia(
            1
        )->centuries(
            1
        )->decades(
            1
        )->years(
            1
        )->months(
            1
        )->weeks(
            1
        )->days(
            1
        )->hours(
            1
        )->minutes(
            1
        )->seconds(
            1
        )->microseconds(
            1
        )->milliseconds(
            1
        )->hoursAndMinutes(
            1,
            1
        )->hoursAndMinutesAndSeconds(
            1,
            1,
            1
        );
        $this->assertEquals(
            '3121-02-09 03:18:11 001001',
            $modified->getDateTime()->format('Y-m-d H:i:s u')
        );
    }

    public static function plusFluidChainProvider(): array
    {
        return [
            'month clamp then day' => ['2025-01-31 00:00:00', 'UTC', [['months', [1]], ['days', [1]]], '2025-03-01 00:00:00 UTC'],
            'day then month (leap year)' => ['2024-01-30 00:00:00', 'UTC', [['days', [1]], ['months', [1]]], '2024-02-29 00:00:00 UTC'],
            'year boundary via seconds' => ['2025-12-31 23:59:59', 'UTC', [['seconds', [1]], ['microseconds', [1]]], '2026-01-01 00:00:00 UTC'],
            'across spring DST' => ['2025-03-29 23:30:00', 'Europe/London', [['hours', [1]], ['hours', [1]]], '2025-03-30 02:30:00 BST'],
            'weeks and negative days use absolute values' => ['2025-01-01 00:00:00', 'UTC', [['weeks', [1]], ['days', [-1]]], '2025-01-09 00:00:00 UTC'],
            'no operations' => ['2025-01-01 00:00:00', 'UTC', [], '2025-01-01 00:00:00 UTC'],
        ];
    }

    #[DataProvider('plusFluidChainProvider')]
    public function testPlusFluidChains(string $input, string $timezone, array $steps, string $expected)
    {
        $format = 'Y-m-d H:i:s T';
        $dt = new DateTime($input, new DateTimeZone($timezone));
        $fluid = Date::modify($dt)->plusFluid();
        foreach ($steps as [$method, $args]) {
            $this->assertSame($fluid, $fluid->{$method}(...$args));
        }
        $this->assertEquals($expected, $fluid->getDateTime()->format($format));
        $this->assertEquals($input, $dt->format('Y-m-d H:i:s'), 'Input mutated in clone mode');
        if ($steps !== []) {
            $this->assertNotSame($dt, $fluid->getDateTime());
        }
    }

    public function testPlusFluidConstructedDirectlyClonesByDefault()
    {
        $dt = new DateTime('2025-01-01 00:00:00', new DateTimeZone('UTC'));
        $fluid = new PlusFluid($dt);
        $this->assertNotSame($dt, $fluid->getDateTime());
        $this->assertEquals($dt, $fluid->getDateTime());
    }

    public function testPlusFluidInPlaceWrapsTheSameInstance()
    {
        $dt = new DateTime('2025-01-01 00:00:00', new DateTimeZone('UTC'));
        $fluid = new PlusFluid($dt, false);
        $this->assertSame($dt, $fluid->getDateTime());
    }

    public function testPlusFluidInPlaceMutatesInput()
    {
        $dt = new DateTime('2025-01-01 00:00:00', new DateTimeZone('UTC'));
        $fluid = new PlusFluid($dt, false);
        $this->assertSame($dt, $fluid->getDateTime());

        $fluid->days(1)->hours(2);
        $this->assertEquals('2025-01-02 02:00:00', $fluid->getDateTime()->format('Y-m-d H:i:s'));
        $this->assertEquals('2025-01-02 02:00:00', $dt->format('Y-m-d H:i:s'));
        $this->assertSame($dt, $fluid->getDateTime());
    }
}
