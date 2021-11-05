<?php
namespace District5\Date\Abstracts;

use DateTime;

/**
 * Class Calculate
 * @package District5\Date\Abstracts
 */
abstract class AbstractConstructor
{
    /**
     * @var DateTime
     */
    protected $dateTime;

    /**
     * Constructor.
     * @param DateTime $dateTime
     */
    public function __construct(DateTime $dateTime)
    {
        $this->dateTime = clone $dateTime;
    }
}
