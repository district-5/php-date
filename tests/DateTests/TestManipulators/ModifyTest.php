<?php

namespace District5Tests\DateTests\TestManipulators;

use DateTime;
use District5\Date\Date;
use PHPUnit\Framework\TestCase;

/**
 * Class ModifyTest
 * @package District5Tests\DateTests\TestManipulators
 */
class ModifyTest extends TestCase
{
    public function testNormal()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 00:15:10');
        $this->assertEquals(
            '2010-01-01 00:15:10',
            Date::modify(
                $dt
            )->withString('+0 seconds')->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            '2010-01-01 00:15:05',
            Date::modify(
                $dt
            )->withString('-5 seconds')->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            '2010-01-01 00:15:20',
            Date::modify(
                $dt
            )->withString('+10 seconds')->format('Y-m-d H:i:s')
        );
        $this->assertFalse(
            Date::modify(
                $dt
            )->withString('this is not valid.')
        );
    }

    public function testNormalInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 00:15:10');
        Date::modify(
            $dt,
            false
        )->withString('+0 seconds');
        $this->assertEquals(
            '2010-01-01 00:15:10',
            $dt->format('Y-m-d H:i:s')
        );

        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 00:15:10');
        Date::modify(
            $dt,
            false
        )->withString('-5 seconds');
        $this->assertEquals(
            '2010-01-01 00:15:05',
            $dt->format('Y-m-d H:i:s')
        );

        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 00:15:10');
        Date::modify(
            $dt,
            false
        )->withString('+10 seconds');
        $this->assertEquals(
            '2010-01-01 00:15:20',
            $dt->format('Y-m-d H:i:s')
        );
    }

    public function testTimeChange()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 00:15:10');
        $this->assertEquals(
            '2010-01-01 01:15:10',
            Date::modify(
                $dt
            )->setHours(1)->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            '2010-01-01 00:25:10',
            Date::modify(
                $dt
            )->setMinutes(25)->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            '2010-01-01 00:15:59',
            Date::modify(
                $dt
            )->setSeconds(59)->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            '2010-01-01 00:15:10.000123',
            Date::modify(
                $dt
            )->setMicroseconds(123)->format('Y-m-d H:i:s.u')
        );
    }

    public function testTimeChangeInPlace()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 00:15:10');

        Date::modify($dt, false)->setHours(1);
        $this->assertEquals(
            '2010-01-01 01:15:10',
            $dt->format('Y-m-d H:i:s')
        );

        $dt = Date::modify($dt, false)->setMinutes(25);
        $this->assertEquals(
            '2010-01-01 01:25:10',
            $dt->format('Y-m-d H:i:s')
        );

        $dt = Date::modify($dt, false)->setSeconds(59);
        $this->assertEquals(
            '2010-01-01 01:25:59',
            $dt->format('Y-m-d H:i:s')
        );

        Date::modify($dt, false)->setMicroseconds(123);
        $this->assertEquals(
            '2010-01-01 01:25:59.000123',
            $dt->format('Y-m-d H:i:s.u')
        );
    }

    public function testChangeTime()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2020-10-02 06:03:01.000001');
        $converted = Date::modify($date)->setTime(5, 4, 3, 2);
        $this->assertEquals(
            '2020-10-02 05:04:03.000002',
            $converted->format('Y-m-d H:i:s.u')
        );
        $converted = Date::modify($date)->setTime(8, 3, 1, 0);
        $this->assertEquals(
            '2020-10-02 08:03:01.000000',
            $converted->format('Y-m-d H:i:s.u')
        );
    }

    public function testChangeDate()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2020-10-02 06:03:01.000001');
        $converted = Date::modify($date)->setDate(1, 2, 3010);
        $this->assertEquals(
            '3010-02-01 06:03:01.000001',
            $converted->format('Y-m-d H:i:s.u')
        );

        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2020-10-02 06:03:01.000001');
        $converted = Date::modify($date)->setDate(15, 12, 2020);
        $this->assertEquals(
            '2020-12-15 06:03:01.000001',
            $converted->format('Y-m-d H:i:s.u')
        );

        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2020-10-02 06:03:01.000001');
        $this->assertFalse(
            Date::modify($date)->setDate(29, 2, 2019)
        );
    }

    public function testChangeTimeInPlace()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2020-10-02 06:03:01.000001');
        Date::modify($date, false)->setTime(5, 4, 3, 2);
        $this->assertEquals(
            '2020-10-02 05:04:03.000002',
            $date->format('Y-m-d H:i:s.u')
        );

        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2020-10-02 06:03:01.000001');
        Date::modify($date, false)->setTime(8, 3, 1, 0);
        $this->assertEquals(
            '2020-10-02 08:03:01.000000',
            $date->format('Y-m-d H:i:s.u')
        );
    }

    public function testChangeDateInPlace()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2020-10-02 06:03:01.000001');
        Date::modify($date, false)->setDate(1, 2, 3010);
        $this->assertEquals(
            '3010-02-01 06:03:01.000001',
            $date->format('Y-m-d H:i:s.u')
        );

        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2020-10-02 06:03:01.000001');
        Date::modify($date, false)->setDate(15, 12, 2020);
        $this->assertEquals(
            '2020-12-15 06:03:01.000001',
            $date->format('Y-m-d H:i:s.u')
        );

        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2020-10-02 06:03:01.000001');
        $performed = Date::modify($date, false)->setDate(29, 2, 2019);
        $this->assertFalse(
            $performed
        );
    }
}
