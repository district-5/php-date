<?php

namespace District5\Date\Validators;

use DateTime;

/**
 * Class DateTimesValidator
 * @package District5\Date\Validators
 */
class DateTimesValidator
{
    /**
     * @var DateTime[]
     */
    protected $dateTimes;

    /**
     * DateTimesValidator constructor.
     * @param DateTime[] $dateTimes
     */
    public function __construct(array $dateTimes)
    {
        $this->dateTimes = $dateTimes;
    }

    /**
     * @return bool
     */
    public function isArrayOfDateTimes(): bool
    {
        foreach ($this->dateTimes as $v) {
            if (!is_object($v) || !$v instanceof DateTime) {
                return false;
            }
        }
        return true;
    }
}
