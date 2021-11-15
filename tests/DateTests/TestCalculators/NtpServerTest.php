<?php

namespace District5Tests\DateTests\TestCalculators;

use DateTime;
use District5\Date\Calculators\Calculate;
use District5\Date\Date;
use PHPUnit\Framework\TestCase;

/**
 * Class NtpServerTest
 * @package District5Tests\DateTests\TestCalculators
 */
class NtpServerTest extends TestCase
{
    public function testReadingDateObject()
    {
        $obj = Date::ntpServer()->getObject();
        $utcNow = Date::time();
        // We give a 2 second leeway because of disparity between the local and NTP server.
        $this->assertGreaterThan(($utcNow-5), $obj->getTimestamp());
        $this->assertLessThan(($utcNow+5), $obj->getTimestamp());
    }

    public function testReadingDateTimestamp()
    {
        $timestamp = Date::ntpServer()->getTimestamp();
        $utcNow = Date::time();
        $this->assertGreaterThan(($utcNow-5), $timestamp);
        $this->assertLessThan(($utcNow+5), $timestamp);
    }
}
