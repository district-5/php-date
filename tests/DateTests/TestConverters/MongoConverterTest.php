<?php

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
