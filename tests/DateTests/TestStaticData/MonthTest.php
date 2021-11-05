<?php

namespace District5Tests\DateTests\TestStaticData;

use DateTime;
use PHPUnit\Framework\TestCase;
use District5\Date\Date;

/**
 * Class MonthTest
 * @package District5Tests\DateTests\TestStaticData
 */
class MonthTest extends TestCase
{
    public function testBeginningOfMonths()
    {
        $this->assertEquals(
            '2020-01-01 00:00:00',
            Date::month()->firstDateInMonth(1, 2020)->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            '2020-02-01 00:00:00',
            Date::month()->firstDateInMonth(2, 2020)->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            '2020-12-01 00:00:00',
            Date::month()->firstDateInMonth(12, 2020)->format('Y-m-d H:i:s')
        );
    }

    public function testBeginningOfMonthsFromDateTime()
    {
        $this->assertEquals(
            '2020-01-01 00:00:00',
            Date::month()->firstDateInMonthFromDateTime(
                DateTime::createFromFormat('Y-m-d H:i:s', '2020-01-15 00:00:00')
            )->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            '2020-02-01 00:00:00',
            Date::month()->firstDateInMonthFromDateTime(
                DateTime::createFromFormat('Y-m-d H:i:s', '2020-02-15 00:00:00')
            )->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            '2020-12-01 00:00:00',
            Date::month()->firstDateInMonthFromDateTime(
                DateTime::createFromFormat('Y-m-d H:i:s', '2020-12-15 00:00:00')
            )->format('Y-m-d H:i:s')
        );
    }

    public function testLastOfMonths()
    {
        $this->assertEquals(
            '2020-01-31 00:00:00',
            Date::month()->lastDateInMonth(1, 2020)->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            '2020-02-29 00:00:00',
            Date::month()->lastDateInMonth(2, 2020)->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            '2019-02-28 00:00:00',
            Date::month()->lastDateInMonth(2, 2019)->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            '2020-12-31 00:00:00',
            Date::month()->lastDateInMonth(12, 2020)->format('Y-m-d H:i:s')
        );
    }

    public function testLastOfMonthsFromDateTime()
    {
        $this->assertEquals(
            '2020-01-31 00:00:00',
            Date::month()->lastDateInMonthFromDateTime(
                DateTime::createFromFormat('Y-m-d H:i:s', '2020-01-15 00:00:00')
            )->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            '2020-02-29 00:00:00',
            Date::month()->lastDateInMonthFromDateTime(
                DateTime::createFromFormat('Y-m-d H:i:s', '2020-02-15 00:00:00')
            )->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            '2019-02-28 00:00:00',
            Date::month()->lastDateInMonthFromDateTime(
                DateTime::createFromFormat('Y-m-d H:i:s', '2019-02-15 00:00:00')
            )->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            '2020-12-31 00:00:00',
            Date::month()->lastDateInMonthFromDateTime(
                DateTime::createFromFormat('Y-m-d H:i:s', '2020-12-15 00:00:00')
            )->format('Y-m-d H:i:s')
        );
    }

    public function testNumberDaysInMonth()
    {
        $dates = [
            '2020-01-31 00:00:00' => 31,
            '2020-01-15 00:00:00' => 31,
            '2020-11-31 00:00:00' => 31,
            '2020-02-15 00:00:00' => 29,
            '2019-02-15 00:00:00' => 28,
        ];
        foreach ($dates as $k => $v) {
            $dt = DateTime::createFromFormat('Y-m-d H:i:s', $k);
            $inst = Date::output($dt);
            $days = Date::month()->numberDaysInMonth(
                $inst->getMonth(),
                $inst->getYear()
            );
            $this->assertEquals($v, $days);

            $daysDt = Date::month()->numberDaysInMonthFromDateTime(
                $dt
            );
            $this->assertEquals($v, $daysDt);
        }
    }
}
