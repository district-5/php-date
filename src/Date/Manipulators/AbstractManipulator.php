<?php

namespace District5\Date\Manipulators;

use DateTime;

/**
 * Class AbstractManipulator
 * @package District5\Date\Manipulators
 */
abstract class AbstractManipulator
{
    /**
     * @var DateTime
     */
    protected $dateTime;

    /**
     * @var bool
     */
    protected $cloneDateTime = true;

    /**
     * Minus constructor.
     * @param DateTime $dateTime
     * @param bool $cloneDateTime (optional) default true, whether to make a clone or alter the instance in place.
     */
    public function __construct(DateTime $dateTime, $cloneDateTime = true)
    {
        $this->cloneDateTime = $cloneDateTime;
        if ($cloneDateTime === true) {
            $this->dateTime = clone $dateTime;
        } else {
            $this->dateTime = $dateTime;
        }
    }

    /**
     * Perform the manipulation against a DateTime using DateInterval of a given $str
     *
     * @param string $str
     * @return DateTime|false
     */
    abstract protected function run(string $str);
}
