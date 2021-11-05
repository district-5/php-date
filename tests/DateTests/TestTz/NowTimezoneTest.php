<?php

namespace District5Tests\DateTests\TestTz;

use District5\Date\Date;
use District5\Date\Tz\TzConstants;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * Class NowTimezoneTest
 * @package District5Tests\DateTests\TestTz
 */
class NowTimezoneTest extends TestCase
{
    public function testZeroOffset()
    {
        $zeros = ['0', '00', '0000', '00:00', '+0', '+00', '+0000', '+00:00'];
        foreach ($zeros as $zero) {
            $nowZeros = Date::now()->fromOffset($zero);
            $this->assertEquals(0, $nowZeros->getOffset());
        }
    }

    public function testOffsetFromString()
    {
        $nowZeros = Date::now()->fromOffset('+0400');
        $this->assertEquals(14400, $nowZeros->getOffset());
    }

    public function testOffsetInvalid()
    {
        $this->assertFalse(Date::now()->fromOffset('+abcdefg'));
    }

    public function testTimezoneString()
    {
        $nowZeros = Date::now()->inTimezone(TzConstants::EUROPE_LONDON);
        $this->assertEquals(TzConstants::EUROPE_LONDON, $nowZeros->getTimezone()->getName());
    }

    public function testTimezoneInvalid()
    {
        $this->assertFalse(Date::now()->fromOffset('This/Is/Not/Valid'));
    }

    public function testUtc()
    {
        try {
            $dt = Date::now()->utc();
            $this->assertEquals(TzConstants::UTC, $dt->getTimezone()->getName());
        } catch (Exception $e) {
            $this->fail();
        }
    }
}
