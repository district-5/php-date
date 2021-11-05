<?php

namespace District5Tests\DateTests\TestCalculators;

use DateTime;
use District5\Date\Calculators\Calculate;
use District5\Date\Date;
use PHPUnit\Framework\TestCase;

/**
 * Class CalculateTest
 * @package District5Tests\DateTests\TestCalculators
 */
class CalculateTest extends TestCase
{
    public function testDaysLeftInMonthStatic()
    {
        $this->assertEquals(26, Calculate::numberDaysLeftInMonthByGivenValues(
            5,
            1,
            2020
        ));
    }

    public function testDateOlderThanInCalculate()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $this->assertTrue(
            Date::calculate($date)->isOlderThan(
                DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 23:03:40.123')
            )
        );
        $this->assertFalse(
            Date::calculate($date)->isOlderThan(
                DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123')
            )
        );
    }

    public function testDateNewerThanInCalculate()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $this->assertTrue(
            Date::calculate($date)->isNewerThan(
                DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 21:03:40.123')
            )
        );
        $this->assertFalse(
            Date::calculate($date)->isNewerThan(
                DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 23:13:40.123')
            )
        );
    }

    public function testDaysLeftInMonthNormal()
    {
        $now = DateTime::createFromFormat('Y-m-d H:i:s', '2020-01-05 01:01:00');
        $this->assertEquals(26, Date::calculate($now)->numberDaysLeftInMonth());
    }

    public function testDaysInMonthStatic()
    {
        $this->assertEquals(31, Calculate::numberDaysInMonthByGivenValues(1, 2020));
    }

    public function testDaysInMonthNormal()
    {
        $now = DateTime::createFromFormat('Y-m-d H:i:s', '2020-01-05 01:01:00');
        $this->assertEquals(31, Date::calculate($now)->numberDaysInMonth());
    }

    public function testDaysLeftInMonthLeapYear()
    {
        $this->assertEquals(27, Calculate::numberDaysLeftInMonthByGivenValues(1, 2, 2019));

        $this->assertEquals(28, Calculate::numberDaysLeftInMonthByGivenValues(1, 2, 2020));
    }

    public function testDaysLeftInMonthLeapYearNormal()
    {
        $now = DateTime::createFromFormat('Y-m-d H:i:s', '2019-02-01 01:01:00');
        $this->assertEquals(27, Date::calculate($now)->numberDaysLeftInMonth());

        $now = DateTime::createFromFormat('Y-m-d H:i:s', '2020-02-01 01:01:00');
        $this->assertEquals(28, Date::calculate($now)->numberDaysLeftInMonth());
    }

    public function testDaysInMonthLeapYearStatic()
    {
        $this->assertEquals(29, Calculate::numberDaysInMonthByGivenValues(2, 2020));

        $this->assertEquals(28, Calculate::numberDaysInMonthByGivenValues(2, 2019));
    }

    public function testDaysInMonthLeapYearNormal()
    {
        $now = DateTime::createFromFormat('Y-m-d H:i:s', '2019-02-05 01:01:00');
        $this->assertEquals(28, Date::calculate($now)->numberDaysInMonth());

        $now = DateTime::createFromFormat('Y-m-d H:i:s', '2020-02-05 01:01:00');
        $this->assertEquals(29, Date::calculate($now)->numberDaysInMonth());
    }
}
