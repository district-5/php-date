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
        $this->markTestSkipped('Issue running with PHP 8.2.6');
        return;
        $obj = Date::ntpServer()->getObject();
        $utcNow = Date::time();
        // We give a 2-second leeway because of disparity between the local and NTP server.
        $this->assertGreaterThan(($utcNow-5), $obj->getTimestamp());
        $this->assertLessThan(($utcNow+5), $obj->getTimestamp());
    }

    public function testReadingDateTimestamp()
    {
        $this->markTestSkipped('Issue running with PHP 8.2.6');
        return;
        $timestamp = Date::ntpServer()->getTimestamp();
        $utcNow = Date::time();
        $this->assertGreaterThan(($utcNow-5), $timestamp);
        $this->assertLessThan(($utcNow+5), $timestamp);
    }
}
