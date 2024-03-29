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

use District5\Date\Date;
use PHPUnit\Framework\TestCase;

/**
 * Class SorterTest
 * @package District5Tests\DateTests\TestFormatters
 */
class SorterTest extends TestCase
{
    public function testOldestToNewestSortSameDate()
    {
        $dtOne = Date::now()->utc();
        $dtTwo = clone $dtOne;
        $dtThree = clone $dtOne;

        $values = Date::sorter()->sortOldestToNewest(
            $dtOne, $dtTwo, $dtThree
        );
        $this->assertCount(3, $values);
        $first = array_shift($values);
        $latest = $first->getTimestamp();
        foreach ($values as $v) {
            $this->assertGreaterThanOrEqual($latest, $v->getTimestamp());
            $latest = $v->getTimestamp();
        }
    }

    public function testOldestToNewestSort()
    {
        $dt = Date::now()->utc();
        $older = Date::modify($dt)->minus()->hours(1);
        $olderAgain = Date::modify($older)->minus()->hours(2);
        $oldest = Date::modify($older)->minus()->hours(3);

        $values = Date::sorter()->sortOldestToNewest(
            $dt, $oldest, $older, $olderAgain
        );
        $this->assertCount(4, $values);
        $first = array_shift($values);
        $latest = $first->getTimestamp();
        foreach ($values as $v) {
            $this->assertGreaterThan($latest, $v->getTimestamp());
            $latest = $v->getTimestamp();
        }
    }

    public function testNewestToOldestSortSameDate()
    {
        $dtOne = Date::now()->utc();
        $dtTwo = clone $dtOne;
        $dtThree = clone $dtOne;

        $values = Date::sorter()->sortNewestToOldest(
            $dtOne, $dtTwo, $dtThree
        );
        $this->assertCount(3, $values);
        $first = array_shift($values);
        $latest = $first->getTimestamp();
        foreach ($values as $v) {
            $this->assertLessThanOrEqual($latest, $v->getTimestamp());
            $latest = $v->getTimestamp();
        }
    }

    public function testNewestToOldestSort()
    {
        $dt = Date::now()->utc();
        $older = Date::modify($dt)->minus()->hours(1);
        $olderAgain = Date::modify($older)->minus()->hours(2);
        $oldest = Date::modify($older)->minus()->hours(3);

        $values = Date::sorter()->sortNewestToOldest(
            $dt, $oldest, $older, $olderAgain
        );
        $this->assertCount(4, $values);
        $first = array_shift($values);
        $latest = $first->getTimestamp();
        foreach ($values as $v) {
            $this->assertLessThan($latest, $v->getTimestamp());
            $latest = $v->getTimestamp();
        }
    }
}
