<?php

namespace District5Tests\DateTests\TestFormatters;

use DateTime;
use District5\Date\Date;
use PHPUnit\Framework\TestCase;

/**
 * Class OutputFormatterTest
 * @package District5Tests\DateTests\TestFormatters
 */
class OutputFormatterTest extends TestCase
{
    public function testYMD()
    {
        $date = DateTime::createFromFormat('Y-m-d', '2019-03-20');
        /** @noinspection PhpRedundantOptionalArgumentInspection */
        $this->assertEquals(
            '2019-03-20',
            Date::output($date)->toYMD(true, '-') // sep is default
        );
        $this->assertEquals(
            '2019|03|20',
            Date::output($date)->toYMD(true, '|')
        );
        $this->assertEquals(
            '20190320',
            Date::output($date)->toYMD(false)
        );
    }

    public function testMDY()
    {
        $date = DateTime::createFromFormat('Y-m-d', '2019-03-20');
        /** @noinspection PhpRedundantOptionalArgumentInspection */
        $this->assertEquals(
            '03-20-2019',
            Date::output($date)->toMDY(true, '-') // sep is default
        );
        $this->assertEquals(
            '03|20|2019',
            Date::output($date)->toMDY(true, '|')
        );
        $this->assertEquals(
            '03202019',
            Date::output($date)->toMDY(false)
        );
    }

    public function testWeek()
    {
        $date = DateTime::createFromFormat('Y-m-d', '2020-01-13');
        $this->assertEquals(
            3,
            Date::output($date)->getWeek()
        );
        $date = DateTime::createFromFormat('Y-m-d', '2020-12-31');
        $this->assertEquals(
            53,
            Date::output($date)->getWeek()
        );
        $date = DateTime::createFromFormat('Y-m-d', '2021-12-31');
        $this->assertEquals(
            52,
            Date::output($date)->getWeek()
        );
    }

    public function testDMY()
    {
        $date = DateTime::createFromFormat('Y-m-d', '2019-03-20');
        /** @noinspection PhpRedundantOptionalArgumentInspection */
        $this->assertEquals(
            '20-03-2019',
            Date::output($date)->toDMY(true, '-') // sep is default
        );
        $this->assertEquals(
            '20|03|2019',
            Date::output($date)->toDMY(true, '|')
        );
        $this->assertEquals(
            '20032019',
            Date::output($date)->toDMY(false)
        );
    }

    public function testUnixTimestamp()
    {
        $sVal = '2019-03-20 20:17:49.000876';
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', $sVal);
        $this->assertEquals(
            $date->getTimestamp(),
            Date::output(
                $date
            )->toUnixTimestamp(false)
        );
        // Next one is an alias anyway.
        $this->assertEquals(
            $date->getTimestamp(),
            Date::output(
                $date
            )->toTimestamp(false)
        );
    }

    public function testMicrosecondTimestamp()
    {
        $sVal = '2019-03-20 20:17:49.000876';
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', $sVal);
        $this->assertEquals(
            $date->format('U.u'),
            Date::output(
                $date
            )->toMicrosecondTimestamp(true)
        );
        // Next one is an alias anyway.
        $this->assertEquals(
            $date->format('U.u'),
            Date::output(
                $date
            )->toMicrosecondTimestamp(false)
        );
    }

    public function testTimeHMAndHMS()
    {
        $sVal = '2019-03-20 20:17:49.000876';
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', $sVal);
        $this->assertEquals(
            $date->format('H:i:s'),
            Date::output(
                $date
            )->toTimeHMS()
        );
        $this->assertEquals(
            $date->format('H:i'),
            Date::output(
                $date
            )->toTimeHM()
        );

        $this->assertEquals(
            $date->format('H|i|s'),
            Date::output(
                $date
            )->toTimeHMS('|')
        );
        $this->assertEquals(
            $date->format('H|i'),
            Date::output(
                $date
            )->toTimeHM('|')
        );
    }

    public function testHourMinuteFormat()
    {
        $sVal = '2019-03-20 20:17:49.000876';
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', $sVal);
        $this->assertEquals('20:17', Date::output($date)->getHourMinutes());
        $this->assertEquals('20:17:49', Date::output($date)->getHourMinutesSeconds());
    }

    public function testPiecesOfDate()
    {
        $sVal = '2019-03-20 20:17:49.000876';
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', $sVal);
        $this->assertEquals(
            2019,
            Date::output($date)->getYear()
        );
        $this->assertEquals(
            3,
            Date::output($date)->getMonth()
        );
        $this->assertEquals(
            20,
            Date::output($date)->getDay()
        );
        $this->assertEquals(
            20,
            Date::output($date)->getHour()
        );
        $this->assertEquals(
            17,
            Date::output($date)->getMinutes()
        );
        $this->assertEquals(
            49,
            Date::output($date)->getSeconds()
        );
        $this->assertEquals(
            876,
            Date::output($date)->getMicroseconds()
        );
        $this->assertEquals(
            0,
            Date::output($date)->getMilliseconds()
        );
    }

    public function testMillisecond()
    {
        $sVal = '2019-03-20 20:17:49.876';
        $date = DateTime::createFromFormat('Y-m-d H:i:s.v', $sVal);
        $this->assertEquals(
            876,
            Date::output($date)->getMilliseconds()
        );
    }

    public function testDaySuffix()
    {
        $date = DateTime::createFromFormat('Y-m-d', '2019-02-01');
        $this->assertEquals('st', Date::output($date)->getDaySuffix());

        $date = DateTime::createFromFormat('Y-m-d', '2019-02-02');
        $this->assertEquals('nd', Date::output($date)->getDaySuffix());

        $date = DateTime::createFromFormat('Y-m-d', '2019-02-03');
        $this->assertEquals('rd', Date::output($date)->getDaySuffix());

        $date = DateTime::createFromFormat('Y-m-d', '2019-02-04');
        $this->assertEquals('th', Date::output($date)->getDaySuffix());
    }

    public function testISOFormat()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $this->assertEquals(
            $date->format(DateTime::ISO8601),
            Date::output(
                $date
            )->toISO8601()
        );
    }

    public function testToFormat()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', '2019-03-20 22:03:40.123');
        $this->assertEquals(
            $date->format('Y-m-d H:i:s.u'),
            Date::output(
                $date
            )->toFormat('Y-m-d H:i:s.u')
        );
    }
}
