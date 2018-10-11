<?php

/**
 * District5 - Date
 *
 * @copyright District5
 *
 * @author Mark Morgan <mark.morgan@district5.co.uk>
 * @link https://www.district5.co.uk
 *
 * @license This software and associated documentation (the "Software") may not be
 * used, copied, modified, distributed, published or licensed to any 3rd party
 * without the written permission of District5 or its author.
 *
 * The above copyright notice and this permission notice shall be included in
 * all licensed copies of the Software.
 *
 */
namespace District5\Utility;

/**
 * DateMongo
 *
 * A utility for working with Mongo UTCDateTime
 *
 * @author Mark Morgan
 *
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
