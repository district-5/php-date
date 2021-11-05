<?php

namespace District5Tests\DateTests;

use DateTime;
use District5\Date\Date;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * Class OldLibTest
 * @package District5Tests\DateTests
 */
class OldLibTest extends TestCase
{
    public function testBasicOldLibrary()
    {
        $nowDate = DateTime::createFromFormat('Y-m-d H:i:s', '2021-11-05 12:13:14');
        $this->assertEquals(
            '2021-11-05 12:13:30',
            \District5\Utility\Date::DatePlusXSeconds($nowDate, 16)->format('Y-m-d H:i:s')
        );
    }
}
