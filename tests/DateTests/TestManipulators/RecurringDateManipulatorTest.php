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
use District5\Date\Calculators\Diff;
use District5\Date\Date;
use District5\Date\Formatters\OutputFormatter;
use District5\Date\Manipulators\Modify;
use District5\Date\Manipulators\RecurringDateManipulator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Class RecurringDateManipulatorTest
 * @package District5Tests\DateTests\TestManipulators
 */
class RecurringDateManipulatorTest extends TestCase
{
    private const FORMAT = 'Y-m-d H:i:s T';

    private static function make(string $value, string $timezone = 'UTC'): DateTime
    {
        return new DateTime($value, new DateTimeZone($timezone));
    }

    public function testThisYearReturnsTheWrappedInstance()
    {
        $dt = self::make('2024-12-25 00:00:00');
        $manipulator = new RecurringDateManipulator($dt);
        $this->assertSame($dt, $manipulator->thisYear());
        $this->assertEquals('2024-12-25 00:00:00 UTC', $manipulator->thisYear()->format(self::FORMAT));
    }

    public static function relativeProvider(): array
    {
        return [
            'tomorrow simple' => ['tomorrow', '2024-12-24 00:00:00', 'UTC', '2024-12-25 00:00:00 UTC'],
            'tomorrow over year boundary' => ['tomorrow', '2024-12-31 23:59:59', 'UTC', '2025-01-01 23:59:59 UTC'],
            'tomorrow into leap day' => ['tomorrow', '2024-02-28 10:00:00', 'UTC', '2024-02-29 10:00:00 UTC'],
            'tomorrow non leap year' => ['tomorrow', '2025-02-28 10:00:00', 'UTC', '2025-03-01 10:00:00 UTC'],
            'tomorrow across spring DST' => ['tomorrow', '2025-03-29 12:00:00', 'Europe/London', '2025-03-30 12:00:00 BST'],
            'yesterday simple' => ['yesterday', '2024-12-25 00:00:00', 'UTC', '2024-12-24 00:00:00 UTC'],
            'yesterday over year boundary' => ['yesterday', '2025-01-01 00:00:00', 'UTC', '2024-12-31 00:00:00 UTC'],
            'yesterday onto leap day' => ['yesterday', '2024-03-01 08:30:00', 'UTC', '2024-02-29 08:30:00 UTC'],
            'yesterday across autumn DST' => ['yesterday', '2025-10-27 12:00:00', 'Europe/London', '2025-10-26 12:00:00 GMT'],
            'nextMonth simple' => ['nextMonth', '2024-11-05 00:00:00', 'UTC', '2024-12-05 00:00:00 UTC'],
            'nextMonth over year boundary' => ['nextMonth', '2024-12-15 00:00:00', 'UTC', '2025-01-15 00:00:00 UTC'],
            'nextMonth clamps Jan 31 (non leap)' => ['nextMonth', '2025-01-31 00:00:00', 'UTC', '2025-02-28 00:00:00 UTC'],
            'nextMonth clamps Jan 31 (leap)' => ['nextMonth', '2024-01-31 00:00:00', 'UTC', '2024-02-29 00:00:00 UTC'],
            'nextMonth clamps Aug 31' => ['nextMonth', '2025-08-31 00:00:00', 'UTC', '2025-09-30 00:00:00 UTC'],
            'lastMonth simple' => ['lastMonth', '2024-12-25 00:00:00', 'UTC', '2024-11-25 00:00:00 UTC'],
            'lastMonth over year boundary' => ['lastMonth', '2025-01-15 00:00:00', 'UTC', '2024-12-15 00:00:00 UTC'],
            'lastMonth clamps Mar 31 (leap)' => ['lastMonth', '2024-03-31 00:00:00', 'UTC', '2024-02-29 00:00:00 UTC'],
            'lastMonth clamps Mar 31 (non leap)' => ['lastMonth', '2025-03-31 00:00:00', 'UTC', '2025-02-28 00:00:00 UTC'],
            'nextYear simple' => ['nextYear', '2024-12-25 00:00:00', 'UTC', '2025-12-25 00:00:00 UTC'],
            'nextYear keeps time' => ['nextYear', '2024-05-04 13:14:15', 'UTC', '2025-05-04 13:14:15 UTC'],
            'nextYear in London summer' => ['nextYear', '2024-07-01 09:00:00', 'Europe/London', '2025-07-01 09:00:00 BST'],
            'lastYear simple' => ['lastYear', '2024-12-25 00:00:00', 'UTC', '2023-12-25 00:00:00 UTC'],
            'lastYear from new years day' => ['lastYear', '2025-01-01 00:00:00', 'UTC', '2024-01-01 00:00:00 UTC'],
        ];
    }

    #[DataProvider('relativeProvider')]
    public function testRelativeMethods(string $method, string $input, string $timezone, string $expected)
    {
        $dt = self::make($input, $timezone);
        $before = $dt->format(self::FORMAT);
        $manipulator = new RecurringDateManipulator($dt);

        $result = $manipulator->{$method}();

        $this->assertInstanceOf(DateTime::class, $result);
        $this->assertEquals($expected, $result->format(self::FORMAT));
        // The wrapped DateTime must not be altered by the relative helpers.
        $this->assertNotSame($dt, $result);
        $this->assertEquals($before, $dt->format(self::FORMAT));
        $this->assertSame($dt, $manipulator->thisYear());
    }

    public function testRelativeMethodsCanBeCalledRepeatedlyWithoutDrift()
    {
        $manipulator = new RecurringDateManipulator(self::make('2024-12-25 00:00:00'));
        $this->assertEquals('2025-12-25', $manipulator->nextYear()->format('Y-m-d'));
        $this->assertEquals('2025-12-25', $manipulator->nextYear()->format('Y-m-d'));
        $this->assertEquals('2023-12-25', $manipulator->lastYear()->format('Y-m-d'));
        $this->assertEquals('2024-12-26', $manipulator->tomorrow()->format('Y-m-d'));
        $this->assertEquals('2024-12-24', $manipulator->yesterday()->format('Y-m-d'));
    }

    public function testOutputWrapsTheDate()
    {
        $manipulator = new RecurringDateManipulator(self::make('2024-12-25 13:14:15'));
        $output = $manipulator->output();
        $this->assertInstanceOf(OutputFormatter::class, $output);
        $this->assertEquals('2024-12-25 13:14:15', $output->toYMDHIS());
        $this->assertEquals(2024, $output->getYear());
        $this->assertEquals(12, $output->getMonth());
        $this->assertEquals(25, $output->getDay());
    }

    public function testDiffWrapsTheDate()
    {
        $manipulator = new RecurringDateManipulator(self::make('2024-12-25 00:00:00'));
        $diff = $manipulator->diff();
        $this->assertInstanceOf(Diff::class, $diff);
        $this->assertEquals(7, $diff->days(self::make('2025-01-01 00:00:00')));
        $this->assertEquals(1, $diff->years(self::make('2025-12-25 00:00:00')));
    }

    public function testModifyWrapsTheDateWithoutMutatingIt()
    {
        $dt = self::make('2024-12-25 00:00:00');
        $manipulator = new RecurringDateManipulator($dt);
        $modify = $manipulator->modify();
        $this->assertInstanceOf(Modify::class, $modify);
        $this->assertEquals('2025-01-01 00:00:00 UTC', $modify->plus()->weeks(1)->format(self::FORMAT));
        $this->assertEquals('2024-12-25 00:00:00 UTC', $dt->format(self::FORMAT));
    }

    public function testGetNextForADateInThePastReturnsNextYear()
    {
        $dt = new DateTime('today');
        $dt->modify('-10 days')->setTime(12, 0);
        $expected = (clone $dt)->modify('+1 year');

        $result = (new RecurringDateManipulator($dt))->getNext();

        $this->assertInstanceOf(DateTime::class, $result);
        $this->assertEquals($expected->format(self::FORMAT), $result->format(self::FORMAT));
        $this->assertNotSame($dt, $result);
    }

    public function testGetNextForADateInTheFutureReturnsTheSameDate()
    {
        $dt = new DateTime('today');
        $dt->modify('+10 days');

        $result = (new RecurringDateManipulator($dt))->getNext();

        $this->assertSame($dt, $result);
    }

    public function testGetPreviousForADateInThePastReturnsTheSameDate()
    {
        $dt = new DateTime('today');
        $dt->modify('-10 days');

        $result = (new RecurringDateManipulator($dt))->getPrevious();

        $this->assertSame($dt, $result);
    }

    public function testGetPreviousForADateInTheFutureKeepsDayAndTimeAndDoesNotMutate()
    {
        $dt = new DateTime('today');
        $dt->modify('+10 days')->setTime(9, 30);
        $before = $dt->format(self::FORMAT);

        $result = (new RecurringDateManipulator($dt))->getPrevious();

        $this->assertInstanceOf(DateTime::class, $result);
        $this->assertNotSame($dt, $result);
        $this->assertEquals($dt->format('m-d H:i:s'), $result->format('m-d H:i:s'));
        $this->assertEquals($before, $dt->format(self::FORMAT));
    }

    public function testGetPreviousForADateInTheFutureReturnsLastYear()
    {
        $dt = new DateTime('today');
        $dt->modify('+10 days');
        $expected = (clone $dt)->modify('-1 year');

        $result = (new RecurringDateManipulator($dt))->getPrevious();

        $this->assertInstanceOf(DateTime::class, $result);
        $this->assertEquals($expected->format('Y-m-d'), $result->format('Y-m-d'));
    }

    public function testRecurringGetNextIsNeverBeforeToday()
    {
        $today = new DateTime('today');
        $next = Date::recurring()->christmasDay()->getNext();
        $this->assertInstanceOf(DateTime::class, $next);
        $this->assertEquals('12-25 00:00:00', $next->format('m-d H:i:s'));
        $this->assertGreaterThanOrEqual($today, $next);
        $this->assertLessThan((clone $today)->modify('+1 year'), $next);
    }

    public function testIsTodayIsTrueForToday()
    {
        $dt = new DateTime('today');
        $this->assertTrue((new RecurringDateManipulator($dt))->isToday());

        $dt = new DateTime('now');
        $this->assertTrue((new RecurringDateManipulator($dt))->isToday());
    }

    public function testIsTodayIsFalseForAnotherDay()
    {
        $dt = new DateTime('today');
        $dt->modify('+10 days');
        $this->assertFalse((new RecurringDateManipulator($dt))->isToday());

        $dt = new DateTime('today');
        $dt->modify('-10 days');
        $this->assertFalse((new RecurringDateManipulator($dt))->isToday());

        $dt = new DateTime('today');
        $dt->modify('+1 day');
        $this->assertFalse((new RecurringDateManipulator($dt))->isToday());
    }

    public function testIsTodayIgnoresTheYearOfTheRecurringDate()
    {
        // 2000 is a leap year, so today's month/day always exists in it.
        $today = new DateTime('today');
        $dt = (clone $today)->setDate(2000, intval($today->format('n')), intval($today->format('j')));
        $this->assertTrue((new RecurringDateManipulator($dt))->isToday());

        $dt = (clone $today)->modify('+1 year')->setTime(23, 59, 59);
        if ($dt->format('m-d') === $today->format('m-d')) {
            $this->assertTrue((new RecurringDateManipulator($dt))->isToday());
        }
    }

    public function testIsTodayForAFixedDateNotToday()
    {
        $today = new DateTime('today');
        $dt = self::make('2000-01-01 00:00:00', $today->getTimezone()->getName());
        $this->assertSame(
            $today->format('m-d') === '01-01',
            (new RecurringDateManipulator($dt))->isToday()
        );
    }
}
