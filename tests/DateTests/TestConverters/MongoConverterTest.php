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

namespace District5Tests\DateTests\TestConverters;

use District5\Date\Date;
use PHPUnit\Framework\TestCase;

/**
 * Class MongoConverterTest
 * @package District5Tests\DateTests\TestConverters
 */
class MongoConverterTest extends TestCase
{
    public function testConvertBetweenMongoFormat()
    {
        if (class_exists('\MongoDB\BSON\UTCDateTime') === false) {
            $this->markTestSkipped('\MongoDB\BSON\UTCDateTime class is not available. Skipping test.');
        }
        $millisecondTimestamp = '1610543228.632';
        $dt = Date::input($millisecondTimestamp)->fromMillisecondTimestamp();
        $mongo = Date::mongo()->convertTo($dt);
        $dateAgain = Date::mongo()->convertFrom($mongo);
        $arrayVersion = (array)$mongo;
        $this->assertEquals($dt->format('Uv'), $arrayVersion['milliseconds']);
        $this->assertEquals($millisecondTimestamp, Date::output($dateAgain)->toMillisecondTimestamp());

        $this->assertEquals($dt->format('Y-m-d H:i:s v'), $dateAgain->format('Y-m-d H:i:s v'));
    }
}
