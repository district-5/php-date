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
use DateTimeImmutable;
use DateTimeZone;
use District5\Date\Date;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Class SorterEdgeCaseTest
 * @package District5Tests\DateTests\TestFormatters
 */
class SorterEdgeCaseTest extends TestCase
{
    private static function make(string $value, string $tz = 'UTC'): DateTime
    {
        return new DateTime($value, new DateTimeZone($tz));
    }

    private static function formatAll(array $dates): array
    {
        return array_map(fn (DateTime $d) => $d->format('Y-m-d H:i:s P'), $dates);
    }

    public function testEmptyInputReturnsEmptyArray(): void
    {
        $this->assertSame([], Date::sorter()->sortOldestToNewest());
        $this->assertSame([], Date::sorter()->sortNewestToOldest());
        $this->assertNull(Date::sorter()->newest([]));
        $this->assertNull(Date::sorter()->oldest([]));
    }

    public function testSingleElement(): void
    {
        $dt = self::make('2024-01-01 00:00:00');
        $this->assertSame([$dt], Date::sorter()->sortOldestToNewest($dt));
        $this->assertSame([$dt], Date::sorter()->sortNewestToOldest($dt));
        $this->assertSame($dt, Date::sorter()->newest([$dt]));
        $this->assertSame($dt, Date::sorter()->oldest([$dt]));
    }

    public function testSortsDistinctValuesInBothDirections(): void
    {
        $a = self::make('2020-01-01 00:00:00');
        $b = self::make('2021-06-15 12:00:00');
        $c = self::make('1999-12-31 23:59:59');
        $d = self::make('2021-06-15 12:00:01');

        $this->assertSame([$c, $a, $b, $d], Date::sorter()->sortOldestToNewest($a, $b, $c, $d));
        $this->assertSame([$d, $b, $a, $c], Date::sorter()->sortNewestToOldest($a, $b, $c, $d));
        $this->assertSame($d, Date::sorter()->newest([$a, $b, $c, $d]));
        $this->assertSame($c, Date::sorter()->oldest([$a, $b, $c, $d]));
    }

    public function testResultIsReindexedList(): void
    {
        $a = self::make('2020-01-02');
        $b = self::make('2020-01-01');
        $sorted = Date::sorter()->sortOldestToNewest($a, $b);
        $this->assertSame([0, 1], array_keys($sorted));
        $this->assertTrue(array_is_list(Date::sorter()->sortNewestToOldest($b, $a)));
    }

    public function testDuplicatesAreKeptAndSortIsStable(): void
    {
        $x1 = self::make('2024-01-01 10:00:00');
        $x2 = self::make('2024-01-01 10:00:00');
        $early = self::make('2024-01-01 09:00:00');
        $late = self::make('2024-01-01 11:00:00');

        $asc = Date::sorter()->sortOldestToNewest($x1, $late, $x2, $early);
        $this->assertCount(4, $asc);
        // Equal values keep their original relative order (x1 before x2).
        $this->assertSame([$early, $x1, $x2, $late], $asc);

        $desc = Date::sorter()->sortNewestToOldest($x2, $early, $x1, $late);
        $this->assertSame([$late, $x2, $x1, $early], $desc);
    }

    public function testMixedTimezonesAreSortedByInstant(): void
    {
        // All three are within one hour of each other in absolute time.
        $london = self::make('2024-06-01 12:30:00', 'Europe/London'); // 11:30 UTC
        $newYork = self::make('2024-06-01 07:00:00', 'America/New_York'); // 11:00 UTC
        $tokyo = self::make('2024-06-01 21:00:00', 'Asia/Tokyo'); // 12:00 UTC

        $this->assertSame([$newYork, $london, $tokyo], Date::sorter()->sortOldestToNewest($tokyo, $london, $newYork));
        $this->assertSame([$tokyo, $london, $newYork], Date::sorter()->sortNewestToOldest($newYork, $tokyo, $london));
        $this->assertSame($tokyo, Date::sorter()->newest([$london, $tokyo, $newYork]));
        $this->assertSame($newYork, Date::sorter()->oldest([$london, $tokyo, $newYork]));
    }

    public function testSameInstantInDifferentTimezonesIsTreatedAsEqual(): void
    {
        $utc = self::make('2024-01-01 12:00:00', 'UTC');
        $paris = self::make('2024-01-01 13:00:00', 'Europe/Paris');

        $this->assertSame([$utc, $paris], Date::sorter()->sortOldestToNewest($utc, $paris));
        $this->assertSame([$paris, $utc], Date::sorter()->sortOldestToNewest($paris, $utc));
    }

    public function testSortingDoesNotModifyInputs(): void
    {
        $a = self::make('2024-01-02', 'Asia/Tokyo');
        $b = self::make('2024-01-01', 'UTC');
        $before = self::formatAll([$a, $b]);
        Date::sorter()->sortOldestToNewest($a, $b);
        Date::sorter()->sortNewestToOldest($a, $b);
        $this->assertSame($before, self::formatAll([$a, $b]));
    }

    public function testInvalidElementsReturnFalseOrNull(): void
    {
        $dt = self::make('2024-01-01');
        $this->assertFalse(Date::sorter()->sortOldestToNewest($dt, new stdClass()));
        $this->assertFalse(Date::sorter()->sortNewestToOldest('2024-01-01', $dt));
        // DateTimeImmutable is not accepted; the library works with mutable DateTime only.
        $this->assertFalse(Date::sorter()->sortOldestToNewest($dt, new DateTimeImmutable('2024-01-01')));
        $this->assertNull(Date::sorter()->newest([$dt, null]));
        $this->assertNull(Date::sorter()->oldest([1704067200, $dt]));
    }

    public function testSubSecondOrdering(): void
    {
        $late = self::make('2024-01-01 10:00:00.900000');
        $early = self::make('2024-01-01 10:00:00.100000');
        $middle = self::make('2024-01-01 10:00:00.500000');
        $oneMicroLater = self::make('2024-01-01 10:00:00.100001');

        $this->assertSame([$early, $oneMicroLater, $middle, $late], Date::sorter()->sortOldestToNewest($late, $middle, $oneMicroLater, $early));
        $this->assertSame([$late, $middle, $oneMicroLater, $early], Date::sorter()->sortNewestToOldest($early, $oneMicroLater, $late, $middle));
        $this->assertSame($late, Date::sorter()->newest([$early, $late, $middle]));
        $this->assertSame($early, Date::sorter()->oldest([$late, $middle, $early]));
    }

    public function testSubSecondOrderingAcrossTimezones(): void
    {
        $tokyo = self::make('2024-01-01 21:00:00.000002', 'Asia/Tokyo'); // 12:00:00.000002Z
        $utc = self::make('2024-01-01 12:00:00.000001', 'UTC');
        $this->assertSame([$utc, $tokyo], Date::sorter()->sortOldestToNewest($tokyo, $utc));
    }
}
