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

namespace DateTests\TestManipulators;

use DateTime;
use District5\Date\Date;
use PHPUnit\Framework\TestCase;

/**
 * Class MinusFluidTest
 * @package District5Tests\DateTests\TestManipulators
 */
class MinusFluidTest extends TestCase
{
    public function testMinusFluidAllOptions()
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s u', '2010-01-01 12:15:09 000000');
        $modified = Date::modify($dt)->minusFluid()->millennia(
            1
        )->centuries(
            1
        )->decades(
            1
        )->years(
            1
        )->months(
            1
        )->weeks(
            1
        )->days(
            1
        )->hours(
            1
        )->minutes(
            1
        )->seconds(
            1
        )->microseconds(
            1
        )->milliseconds(
            1
        )->hoursAndMinutes(
            1,
            1
        )->hoursAndMinutesAndSeconds(
            1,
            1,
            1
        );
        // '2010-01-01 12:15:09 000000'
        $this->assertEquals(
            '0898-11-23 09:12:06 998999',
            $modified->getDateTime()->format('Y-m-d H:i:s u')
        );
    }
}
