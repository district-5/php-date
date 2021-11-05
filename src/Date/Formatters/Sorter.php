<?php

namespace District5\Date\Formatters;

use DateTime;
use District5\Date\Date;

/**
 * Class Sorter
 * @package District5\Date\Formatters
 */
class Sorter
{
    /**
     * @param DateTime ...$args
     * @return DateTime[]|false
     */
    public function sortOldestToNewest(...$args)
    {
        if (empty($args)) {
            return [];
        }
        if (false === Date::validateArray($args)->isArrayOfDateTimes()) {
            return false;
        }
        uasort($args, function ($a, $b) {
            if ($a->getTimestamp() === $b->getTimestamp()) {
                return 0;
            }

            return ($a->getTimestamp() < $b->getTimestamp()) ? -1 : 1;
        });
        return array_values($args);
    }

    /**
     * @param DateTime ...$args
     * @return DateTime[]|false
     */
    public function sortNewestToOldest(...$args)
    {
        if (empty($args)) {
            return [];
        }
        if (false === Date::validateArray($args)->isArrayOfDateTimes()) {
            return false;
        }
        uasort($args, function ($a, $b) {
            if ($a->getTimestamp() === $b->getTimestamp()) {
                return 0;
            }

            return ($a->getTimestamp() > $b->getTimestamp()) ? -1 : 1;
        });
        return array_values($args);
    }
}
