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

namespace District5Tests\DateTests;

use DateTime;
use District5\Date\Date;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * Class DateTest
 * @package District5Tests\DateTests
 */
class DateTest extends TestCase
{
    public function testCreateFromString()
    {
        $this->assertEquals(
            '2045-01-02 00:00:00',
            Date::fromString('First Monday of January 2045')->format('Y-m-d H:i:s')
        );
        $this->assertFalse(
            Date::fromString('This has nothing to do with a date.')
        );
    }

    public function testEpoch()
    {
        $epoch = Date::epoch();
        $this->assertEquals(
            '1970-01-01 00:00:00',
            $epoch->format('Y-m-d H:i:s')
        );
        $this->assertEquals('000', $epoch->format('v'));
        $this->assertEquals('0', $epoch->format('U'));
        $this->assertEquals('000000', $epoch->format('u'));
    }

    public function testCreateYMDHISM()
    {
        try {
            $dt = Date::now()->utc();
            $this->assertEquals(
                $dt->format('U.u'),
                Date::createYMDHISM(
                    intval($dt->format('Y')),
                    intval($dt->format('m')),
                    intval($dt->format('d')),
                    intval($dt->format('H')),
                    intval($dt->format('i')),
                    intval($dt->format('s')),
                    intval($dt->format('u'))
                )->format('U.u')
            );
        } catch (Exception $e) {
            $this->fail();
        }
    }

    public function testNowDefaultMatchesExpanded()
    {
        $nowDefaultOne = Date::nowDefault()->format('Y-m-d H:i');
        $nowDefaultTwo = Date::now()->default()->format('Y-m-d H:i');
        $this->assertEquals($nowDefaultOne, $nowDefaultTwo);
    }

    public function testNowUtcMatchesExpanded()
    {
        $nowDefaultOne = Date::nowUtc()->format('Y-m-d H:i');
        $nowDefaultTwo = Date::now()->utc()->format('Y-m-d H:i');
        $this->assertEquals($nowDefaultOne, $nowDefaultTwo);
    }

    public function testValidateArrayOfDateTimes()
    {
        $datesValid = [
            Date::nowUtc(),
            Date::nowDefault()
        ];
        $this->assertTrue(Date::validateArray($datesValid)->isArrayOfDateTimes());

        $datesValid = [
            Date::nowUtc(),
            'foo',
            Date::nowDefault()
        ];
        $this->assertFalse(Date::validateArray($datesValid)->isArrayOfDateTimes());
    }

    public function testAge()
    {
        $date = DateTime::createFromFormat('Y-m-d', '1984-01-02');
        $this->assertEquals(
            Date::diff($date)->years(),
            Date::age($date)
        );
    }
}
