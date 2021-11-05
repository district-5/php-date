<?php /** @noinspection PhpFullyQualifiedNameUsageInspection */

namespace District5\Date\Converters;

use DateTime;

/**
 * Class Calculate
 * @package District5\Date\Converters
 */
abstract class ConverterAbstract
{
    /**
     * Convert a DateTime to an 'x' object.
     *
     * @param DateTime $dateTime
     * @return mixed
     */
    abstract public function convertTo(DateTime $dateTime);

    /**
     * Convert an 'x' object to a DateTime object.
     *
     * @param mixed $provided
     * @return DateTime|false
     */
    abstract public function convertFrom($provided);
}
