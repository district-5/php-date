<?php

namespace District5Tests\DateTests\TestFormatters;

use DateTime;
use District5\Date\Date;
use PHPUnit\Framework\TestCase;

/**
 * Class InputFormatterTest
 * @package District5Tests\DateTests\TestFormatters
 */
class InputFormatterTest extends TestCase
{
    public function testYMDHIS()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2020-01-15 04:30:54');
        $new = Date::input($dt->format('Y-m-d H:i:s'))->fromYMDHIS();
        $this->assertEquals(
            $dt->format('Y-m-d H:i:s'),
            $new->format('Y-m-d H:i:s')
        );

        $this->assertFalse(Date::input($dt->format('YmdHis'))->fromYMDHIS());

        $new = Date::input($dt->format('YmdHis'))->fromFormat('YmdHis');
        $this->assertEquals(
            $dt->format('Y-m-d H:i:s'),
            $new->format('Y-m-d H:i:s')
        );
    }

    public function testYMD()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        /** @noinspection PhpRedundantOptionalArgumentInspection */
        $this->assertEquals(
            $date->format('Y-m-d'),
            Date::input(
                $date->format('Y-m-d')
            )->fromYMD(
                '-' // default
            )->format(
                'Y-m-d'
            )
        );

        $date2 = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $this->assertEquals(
            $date2->format('Y/m/d'),
            Date::input(
                $date2->format('Y/m/d')
            )->fromYMD(
                '/'
            )->format(
                'Y/m/d'
            )
        );
    }

    public function testMDY()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        /** @noinspection PhpRedundantOptionalArgumentInspection */
        $this->assertEquals(
            $date->format('m-d-Y'),
            Date::input(
                $date->format('m-d-Y')
            )->fromMDY(
                '-' // default
            )->format(
                'm-d-Y'
            )
        );

        $date2 = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $this->assertEquals(
            $date2->format('m/d/Y'),
            Date::input(
                $date2->format('m/d/Y')
            )->fromMDY(
                '/'
            )->format(
                'm/d/Y'
            )
        );
    }

    public function testDMY()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        /** @noinspection PhpRedundantOptionalArgumentInspection */
        $this->assertEquals(
            $date->format('d-m-Y'),
            Date::input(
                $date->format('d-m-Y')
            )->fromDMY(
                '-' // default
            )->format(
                'd-m-Y'
            )
        );

        $date2 = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $this->assertEquals(
            $date2->format('d/m/Y'),
            Date::input(
                $date2->format('d/m/Y')
            )->fromDMY(
                '/'
            )->format(
                'd/m/Y'
            )
        );
    }

    public function testTimestamp()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $this->assertEquals(
            $date->getTimestamp(),
            Date::input(
                $date->format('U')
            )->fromTimestamp()->getTimestamp()
        );
    }

    public function testUnixTimestamp()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $this->assertEquals(
            $date->getTimestamp(),
            Date::input(
                $date->format('U')
            )->fromUnixTimestamp()->getTimestamp()
        );
    }

    public function testInvalidUnixTimestamp()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $this->assertEquals(
            $date->format('U'),
            Date::input(
                $date->format('U.u')
            )->fromUnixTimestamp(
                false
            )->getTimestamp()
        );

        $this->assertFalse(Date::input($date->format('U.u'))->fromUnixTimestamp(true));
    }

    public function testMicrosecondTimestamp()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $this->assertEquals(
            $date->format('U.u'),
            Date::input(
                $date->format('U.u')
            )->fromMicrosecondTimestamp()->format('U.u')
        );
    }

    public function testMillisecondTimestamp()
    {
        $date = DateTime::createFromFormat('U.u', '1610549639.876');
        $this->assertEquals(
            $date->format('U.v'),
            Date::input(
                $date->format('U.v')
            )->fromMillisecondTimestamp()->format('U.v')
        );
        $this->assertEquals('876', $date->format('v'));
        $this->assertEquals('876000', $date->format('u'));
    }

    public function testInvalidMicrosecondTimestamp()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $this->assertEquals(
            $date->format('U'),
            Date::input(
                $date->format('U')
            )->fromMicrosecondTimestamp(
                false
            )->format('U')
        );

        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $this->assertFalse(
            Date::input($date->format('U'))->fromMicrosecondTimestamp(true)
        );
    }

    public function testISOFormat()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $this->assertEquals(
            $date->format('U'),
            Date::input(
                $date->format(DateTime::ISO8601)
            )->fromISO8601()->format('U')
        );
    }
}
