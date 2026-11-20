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

namespace District5Tests\DateTests\TestCalculators;

use DateTime;
use DateTimeZone;
use District5\Date\Date;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Class DiffEdgeCaseTest
 *
 * Edge cases for Diff: argument order, DST, leap days, month ends and mixed timezones.
 *
 * @package District5Tests\DateTests\TestCalculators
 */
class DiffEdgeCaseTest extends TestCase
{
    private static function dt(string $value, string $tz = 'UTC'): DateTime
    {
        return new DateTime($value, new DateTimeZone($tz));
    }

    /**
     * [first, second, timezone, method, expected]
     * Each case is asserted in both argument orders, as Diff returns absolute values.
     */
    public static function symmetricProvider(): array
    {
        return [
            // Same instant
            'same instant seconds' => ['2024-05-05 10:00:00', '2024-05-05 10:00:00', 'UTC', 'seconds', 0],
            'same instant minutes' => ['2024-05-05 10:00:00', '2024-05-05 10:00:00', 'UTC', 'minutes', 0],
            'same instant hours' => ['2024-05-05 10:00:00', '2024-05-05 10:00:00', 'UTC', 'hours', 0],
            'same instant days' => ['2024-05-05 10:00:00', '2024-05-05 10:00:00', 'UTC', 'days', 0],
            'same instant weeks' => ['2024-05-05 10:00:00', '2024-05-05 10:00:00', 'UTC', 'weeks', 0],
            'same instant months' => ['2024-05-05 10:00:00', '2024-05-05 10:00:00', 'UTC', 'months', 0],
            'same instant years' => ['2024-05-05 10:00:00', '2024-05-05 10:00:00', 'UTC', 'years', 0],
            'same instant milliseconds' => ['2024-05-05 10:00:00.123', '2024-05-05 10:00:00.123', 'UTC', 'milliseconds', 0],

            // DST spring forward (London, last Sunday of March 2024: 01:00 -> 02:00) - a 23 hour calendar day
            'spring DST day hours' => ['2024-03-31 00:00:00', '2024-04-01 00:00:00', 'Europe/London', 'hours', 23],
            'spring DST day minutes' => ['2024-03-31 00:00:00', '2024-04-01 00:00:00', 'Europe/London', 'minutes', 1380],
            'spring DST day seconds' => ['2024-03-31 00:00:00', '2024-04-01 00:00:00', 'Europe/London', 'seconds', 82800],
            'spring DST day days' => ['2024-03-31 00:00:00', '2024-04-01 00:00:00', 'Europe/London', 'days', 1],
            'spring DST gap 00:30-02:30 is 1 hour' => ['2024-03-31 00:30:00', '2024-03-31 02:30:00', 'Europe/London', 'hours', 1],
            'spring DST week is 1 week' => ['2024-03-25 00:00:00', '2024-04-01 00:00:00', 'Europe/London', 'weeks', 1],

            // DST fall back (London, last Sunday of October 2024: 02:00 -> 01:00) - a 25 hour calendar day
            'autumn DST day hours' => ['2024-10-27 00:00:00', '2024-10-28 00:00:00', 'Europe/London', 'hours', 25],
            'autumn DST day minutes' => ['2024-10-27 00:00:00', '2024-10-28 00:00:00', 'Europe/London', 'minutes', 1500],
            'autumn DST day seconds' => ['2024-10-27 00:00:00', '2024-10-28 00:00:00', 'Europe/London', 'seconds', 90000],
            'autumn DST day days' => ['2024-10-27 00:00:00', '2024-10-28 00:00:00', 'Europe/London', 'days', 1],

            // Leap days
            'leap year Feb 28 -> Mar 1' => ['2024-02-28 00:00:00', '2024-03-01 00:00:00', 'UTC', 'days', 2],
            'non leap year Feb 28 -> Mar 1' => ['2023-02-28 00:00:00', '2023-03-01 00:00:00', 'UTC', 'days', 1],
            'leap year length in days' => ['2024-01-01 00:00:00', '2025-01-01 00:00:00', 'UTC', 'days', 366],
            'non leap year length in days' => ['2023-01-01 00:00:00', '2024-01-01 00:00:00', 'UTC', 'days', 365],
            'leap year length in weeks' => ['2024-01-01 00:00:00', '2025-01-01 00:00:00', 'UTC', 'weeks', 52],
            'leap year length in seconds' => ['2024-01-01 00:00:00', '2025-01-01 00:00:00', 'UTC', 'seconds', 31622400],
            'leap day to leap day years' => ['2024-02-29 00:00:00', '2028-02-29 00:00:00', 'UTC', 'years', 4],
            'leap day to leap day months' => ['2024-02-29 00:00:00', '2028-02-29 00:00:00', 'UTC', 'months', 48],
            'leap day to leap day days' => ['2024-02-29 00:00:00', '2028-02-29 00:00:00', 'UTC', 'days', 1461],
            'leap day to Feb 28 next year is 11 months' => ['2024-02-29 00:00:00', '2025-02-28 00:00:00', 'UTC', 'months', 11],
            'leap day to Mar 1 next year is 12 months' => ['2024-02-29 00:00:00', '2025-03-01 00:00:00', 'UTC', 'months', 12],

            // Month ends
            'Jan 31 -> Feb 29 (leap) is 0 months' => ['2024-01-31 00:00:00', '2024-02-29 00:00:00', 'UTC', 'months', 0],
            'Jan 31 -> Feb 28 (non leap) is 0 months' => ['2023-01-31 00:00:00', '2023-02-28 00:00:00', 'UTC', 'months', 0],
            'Jan 31 -> Feb 29 days' => ['2024-01-31 00:00:00', '2024-02-29 00:00:00', 'UTC', 'days', 29],
            'Jan 31 -> Mar 31 is 2 months' => ['2024-01-31 00:00:00', '2024-03-31 00:00:00', 'UTC', 'months', 2],
            'one second short of a month' => ['2024-04-15 00:00:00', '2024-05-14 23:59:59', 'UTC', 'months', 0],
            'exactly a month' => ['2024-04-15 00:00:00', '2024-05-15 00:00:00', 'UTC', 'months', 1],
            'one second short of a day' => ['2024-04-15 00:00:00', '2024-04-15 23:59:59', 'UTC', 'days', 0],
            'one second short of a week' => ['2024-04-15 00:00:00', '2024-04-21 23:59:59', 'UTC', 'weeks', 0],
            'exactly a week' => ['2024-04-15 00:00:00', '2024-04-22 00:00:00', 'UTC', 'weeks', 1],

            // Decade / century / millennium boundaries
            'decade boundary minus a day' => ['2000-01-01 00:00:00', '2009-12-31 00:00:00', 'UTC', 'decades', 0],
            'decade boundary' => ['2000-01-01 00:00:00', '2010-01-01 00:00:00', 'UTC', 'decades', 1],
            'century boundary minus a day' => ['1900-01-01 00:00:00', '1999-12-31 00:00:00', 'UTC', 'centuries', 0],
            'century boundary' => ['1900-01-01 00:00:00', '2000-01-01 00:00:00', 'UTC', 'centuries', 1],
            'centuries floor' => ['1800-06-01 00:00:00', '2099-06-01 00:00:00', 'UTC', 'centuries', 2],
            'millennium boundary minus a day' => ['1000-01-01 00:00:00', '1999-12-31 00:00:00', 'UTC', 'millennia', 0],
            'millennium boundary' => ['1000-01-01 00:00:00', '2000-01-01 00:00:00', 'UTC', 'millennia', 1],

            // Sub-second and pre-epoch
            'milliseconds across the epoch' => ['1969-12-31 23:59:59.750', '1970-01-01 00:00:00.250', 'UTC', 'milliseconds', 500],
            'milliseconds truncate microseconds' => ['2024-01-01 00:00:00.000000', '2024-01-01 00:00:00.001999', 'UTC', 'milliseconds', 1],
            'sub millisecond is 0' => ['2024-01-01 00:00:00.000000', '2024-01-01 00:00:00.000999', 'UTC', 'milliseconds', 0],
            'milliseconds over a day' => ['2024-01-01 00:00:00.000', '2024-01-02 00:00:00.001', 'UTC', 'milliseconds', 86400001],
            'seconds before the epoch' => ['1969-12-31 23:59:00', '1970-01-01 00:01:00', 'UTC', 'seconds', 120],
            'hours below the half hour' => ['2024-01-01 00:00:00', '2024-01-01 01:29:59', 'UTC', 'hours', 1],
            'minutes below the half minute' => ['2024-01-01 00:00:00', '2024-01-01 00:01:29', 'UTC', 'minutes', 1],
        ];
    }

    #[DataProvider('symmetricProvider')]
    public function testSymmetricDiff(string $first, string $second, string $tz, string $method, int $expected)
    {
        $a = self::dt($first, $tz);
        $b = self::dt($second, $tz);
        $this->assertSame($expected, Date::diff($a)->$method($b), 'forward');
        $this->assertSame($expected, Date::diff($b)->$method($a), 'reversed');
    }

    public function testSameInstantInDifferentTimezonesIsZero()
    {
        $utc = self::dt('2024-06-01 12:00:00');
        $newYork = self::dt('2024-06-01 08:00:00', 'America/New_York');
        $tokyo = self::dt('2024-06-01 21:00:00', 'Asia/Tokyo');

        foreach ([$newYork, $tokyo] as $other) {
            $this->assertSame(0, Date::diff($utc)->seconds($other));
            $this->assertSame(0, Date::diff($utc)->milliseconds($other));
            $this->assertSame(0, Date::diff($utc)->hours($other));
            $this->assertSame(0, Date::diff($utc)->days($other));
            $this->assertSame(0, Date::diff($utc)->months($other));
            $this->assertSame(0, Date::diff($utc)->years($other));
        }
    }

    public function testDifferentTimezonesUseTheRealInstant()
    {
        // Calendar dates differ by one day, but the instants are only 12 hours apart.
        $utc = self::dt('2024-06-01 12:00:00');
        $tokyo = self::dt('2024-06-02 09:00:00', 'Asia/Tokyo'); // 2024-06-02 00:00:00 UTC

        $this->assertSame(12, Date::diff($utc)->hours($tokyo));
        $this->assertSame(12, Date::diff($tokyo)->hours($utc));
        $this->assertSame(43200, Date::diff($tokyo)->seconds($utc));
        $this->assertSame(0, Date::diff($utc)->days($tokyo));
        $this->assertSame(0, Date::diff($tokyo)->days($utc));
    }

    /**
     * [birth date, on date, expected age in years]
     */
    public static function ageProvider(): array
    {
        return [
            'day before birthday' => ['1990-06-15', '2024-06-14', 33],
            'on birthday' => ['1990-06-15', '2024-06-15', 34],
            'day after birthday' => ['1990-06-15', '2024-06-16', 34],
            'born on day, zero years' => ['2024-06-15', '2024-06-15', 0],
            'one day short of first birthday' => ['2023-06-15', '2024-06-14', 0],
            'Feb 29 birthday, Feb 28 non leap year' => ['2000-02-29', '2023-02-28', 22],
            'Feb 29 birthday, Mar 1 non leap year' => ['2000-02-29', '2023-03-01', 23],
            'Feb 29 birthday, Feb 28 leap year' => ['2000-02-29', '2024-02-28', 23],
            'Feb 29 birthday, Feb 29 leap year' => ['2000-02-29', '2024-02-29', 24],
            'Dec 31 birthday, Jan 1' => ['1999-12-31', '2025-01-01', 25],
            'Jan 1 birthday, Dec 31' => ['2000-01-01', '2024-12-31', 24],
        ];
    }

    #[DataProvider('ageProvider')]
    public function testYearsAsAge(string $born, string $on, int $expected)
    {
        $bornDt = self::dt($born . ' 00:00:00', 'Europe/London');
        $onDt = self::dt($on . ' 00:00:00', 'Europe/London');
        $this->assertSame($expected, Date::diff($bornDt)->years($onDt));
        $this->assertSame($expected, Date::diff($onDt)->years($bornDt));
    }

    public function testDecadesCenturiesMillenniaFromWholeYears()
    {
        $a = self::dt('0500-01-01 00:00:00');
        $b = self::dt('2999-12-31 00:00:00'); // 2499 whole years
        $this->assertSame(2499, Date::diff($a)->years($b));
        $this->assertSame(249, Date::diff($a)->decades($b));
        $this->assertSame(24, Date::diff($a)->centuries($b));
        $this->assertSame(2, Date::diff($a)->millennia($b));
        $this->assertSame(2, Date::diff($b)->millennia($a));
    }

    public function testDiffDoesNotMutateInputs()
    {
        $a = self::dt('2024-03-31 00:00:00', 'Europe/London');
        $b = self::dt('2024-10-27 00:00:00', 'Europe/London');
        $aBefore = $a->format('Y-m-d H:i:s.u e');
        $bBefore = $b->format('Y-m-d H:i:s.u e');

        $diff = Date::diff($a);
        foreach (['millennia', 'centuries', 'decades', 'years', 'months', 'weeks', 'days', 'hours', 'minutes', 'seconds', 'milliseconds'] as $method) {
            $diff->$method($b);
        }

        $this->assertSame($aBefore, $a->format('Y-m-d H:i:s.u e'));
        $this->assertSame($bBefore, $b->format('Y-m-d H:i:s.u e'));
    }

    public function testDiffIsBoundToTheDateAtConstruction()
    {
        // AbstractConstructor clones the DateTime, so later changes to the original do not leak in.
        $a = self::dt('2024-01-01 00:00:00');
        $diff = Date::diff($a);
        $a->modify('+10 days');
        $this->assertSame(10, $diff->days($a));
        $this->assertSame(240, $diff->hours($a));
    }

    public function testNullOtherDateUsesNow()
    {
        $tenDaysAgo = Date::nowDefault()->modify('-10 days');
        $this->assertSame(10, Date::diff($tenDaysAgo)->days());
        $this->assertSame(1, Date::diff($tenDaysAgo)->weeks());

        $fiveYearsAgo = Date::nowDefault()->modify('-5 years')->modify('-1 day');
        $this->assertSame(5, Date::diff($fiveYearsAgo)->years());
        $this->assertSame(60, Date::diff($fiveYearsAgo)->months());
        $this->assertSame(0, Date::diff($fiveYearsAgo)->decades());
        $this->assertSame(0, Date::diff($fiveYearsAgo)->centuries());
        $this->assertSame(0, Date::diff($fiveYearsAgo)->millennia());

        $twoHoursAgo = Date::nowDefault()->modify('-2 hours');
        $this->assertSame(2, Date::diff($twoHoursAgo)->hours());
        $this->assertSame(120, Date::diff($twoHoursAgo)->minutes());
        $seconds = Date::diff($twoHoursAgo)->seconds();
        $this->assertGreaterThanOrEqual(7200, $seconds);
        $this->assertLessThanOrEqual(7202, $seconds);
        $ms = Date::diff($twoHoursAgo)->milliseconds();
        $this->assertGreaterThanOrEqual(7200000, $ms);
        $this->assertLessThan(7202000, $ms);
    }

    public function testMonthsIsSymmetricAtMonthEnd()
    {
        $jan31 = self::dt('2024-01-31 00:00:00');
        $mar1 = self::dt('2024-03-01 00:00:00');
        $this->assertSame(Date::diff($jan31)->months($mar1), Date::diff($mar1)->months($jan31));
    }
}
