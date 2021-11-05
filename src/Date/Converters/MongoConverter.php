<?php /** @noinspection PhpFullyQualifiedNameUsageInspection */

namespace District5\Date\Converters;

use DateTime;
use District5\Date\Date;

/**
 * Class MongoConverter
 *
 * The references to Mongo classes are purposely hidden to avoid any runtime errors if the driver is not installed.
 *
 * @package District5\Date\Converters
 */
class MongoConverter extends ConverterAbstract
{
    /**
     * Convert a DateTime to a UTCDateTime object.
     *
     * @param DateTime $dateTime
     * @return \MongoDB\BSON\UTCDateTime|false
     * @noinspection PhpComposerExtensionStubsInspection
     */
    public function convertTo(DateTime $dateTime)
    {
        $cl = '\MongoDB\BSON\UTCDateTime';
        if (class_exists($cl) === false) {
            return false;
        }

        return new $cl((Date::output($dateTime)->toMillisecondTimestamp() * 1000));
    }

    /**
     * Convert a UTCDateTime to a DateTime object.
     *
     * @param \MongoDB\BSON\UTCDateTime $provided
     * @return DateTime|false
     * @noinspection PhpComposerExtensionStubsInspection
     * @noinspection PhpMissingParamTypeInspection
     */
    public function convertFrom($provided)
    {
        if (!is_object($provided) || get_class($provided) !== 'MongoDB\BSON\UTCDateTime') {
            return false;
        }

        return $provided->toDateTime();
    }
}
