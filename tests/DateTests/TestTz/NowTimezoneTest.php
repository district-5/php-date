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
