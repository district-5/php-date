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

namespace District5\Utility;

/**
 * DateMongo
 *
 * A utility for working with Mongo UTCDateTime
 */
class DateMongo
{

    /**
     * Converts a PHP DateTime to a MongoDB UTCDateTime
     *
     * @param \DateTime $dateTime
     *
     * @return \MongoDB\BSON\UTCDateTime
     */
    public static function PHPDateTimeToMongoUTCDateTime($dateTime)
    {
        return new \MongoDB\BSON\UTCDateTime(1000 * ($dateTime->getTimestamp()));
    }

    /**
     * Converts a MongoDB UTCDateTime to a regular PHP DateTime
     *
     * @param \MongoDB\BSON\UTCDateTime $mongoUTCDateTime
     *
     * @return \DateTime
     */
    public static function MongoUTCDateTimeToPHPDateTime($mongoUTCDateTime)
    {
        $timestamp = static::MongoUTCDateTimeToTimestampInt($mongoUTCDateTime);

        $datetime = new \DateTime();
        $datetime->setTimestamp($timestamp);

        return $datetime;
    }

    /**
     * Converts a MongoDB UTCDateTime to a unix timestamp int
     *
     * @param \MongoDB\BSON\UTCDateTime $mongoUTCDateTime
     *
     * @return int
     */
    public static function MongoUTCDateTimeToTimestampInt($mongoUTCDateTime)
    {
        $timestampMillis = (int)($mongoUTCDateTime->__toString());
        $timestamp = (int)($timestampMillis / 1000);

        return $timestamp;
    }

    /**
     * Converts a MongoDB UTCDateTime to a millisecond precision timestamp int
     *
     * @param \MongoDB\BSON\UTCDateTime $mongoUTCDateTime
     *
     * @return int
     */
    public static function MongoUTCDateTimeToTimestampMillisInt($mongoUTCDateTime)
    {
        return (int)($mongoUTCDateTime->__toString());
    }
}
