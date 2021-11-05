<?php

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
