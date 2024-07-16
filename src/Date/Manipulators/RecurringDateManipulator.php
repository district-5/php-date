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

namespace District5\Date\Manipulators;

use DateTime;
use District5\Date\Calculators\Diff;
use District5\Date\Date;
use District5\Date\Formatters\OutputFormatter;
use Exception;

/**
 * Class RecurringDateManipulator
 * @package District5\Date\Manipulators
 */
class RecurringDateManipulator
{
    /**
     * @var DateTime
     */
    private DateTime $dateTime;

    /**
     * RecurringDateManipulator constructor.
     * @param DateTime $dateTime
     */
    public function __construct(DateTime $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    /**
     * @return DateTime
     */
    public function thisYear(): DateTime
    {
        return $this->dateTime;
    }

    /**
     * @return DateTime|false
     * @noinspection PhpUnused
     */
    public function tomorrow(): DateTime|bool
    {
        return Date::modify($this->dateTime)->plus()->days(1);
    }

    /**
     * @return DateTime|false
     * @noinspection PhpUnused
     */
    public function yesterday(): DateTime|bool
    {
        return Date::modify($this->dateTime)->minus()->days(1);
    }

    /**
     * @return DateTime|false
     * @noinspection PhpUnused
     */
    public function nextMonth(): DateTime|bool
    {
        return Date::modify($this->dateTime)->plus()->months(1);
    }

    /**
     * @return DateTime|false
     * @noinspection PhpUnused
     */
    public function lastMonth(): DateTime|bool
    {
        return Date::modify($this->dateTime)->minus()->months(1);
    }

    /**
     * @return DateTime|false
     */
    public function nextYear(): DateTime|bool
    {
        return Date::modify($this->dateTime)->plus()->years(1);
    }

    /**
     * @return DateTime|false
     */
    public function lastYear(): DateTime|bool
    {
        return Date::modify($this->dateTime)->minus()->years(1);
    }

    /**
     * @return bool
     * @noinspection PhpUnused
     */
    public function isToday(): bool
    {
        try {
            $dt = Date::now()->default();
            $start = $this->generateNormalisedDate();
            $end = DateTime::createFromFormat(
                'Y-m-d H:i:s',
                sprintf(
                    '%s-%s-%s 23:59:59',
                    $dt->format('Y'),
                    $dt->format('m'),
                    $dt->format('d')
                )
            );
            if ($dt >= $start && $dt <= $end) {
                return true;
            }
        } catch (Exception) {
        }
        return false;
    }

    /**
     * Get the previous instance of this recurring date.
     *
     * @return DateTime|false
     * @noinspection PhpUnused
     */
    public function getPrevious(): DateTime|bool
    {
        try {
            $date = $this->generateNormalisedDate();
            if (Date::validateObject($this->dateTime)->isNewerThan($date)) {
                return $this->nextYear();
            }
            return $this->dateTime;
        } catch (Exception) {
        }
        return false;
    }

    /**
     * Get the next instance of this recurring date.
     *
     * @return DateTime|false
     * @noinspection PhpUnused
     */
    public function getNext(): DateTime|bool
    {
        try {
            $date = $this->generateNormalisedDate();
            if (Date::validateObject($this->dateTime)->isOlderThan($date)) {
                return $this->nextYear();
            }
            return $this->dateTime;
        } catch (Exception) {
        }
        return false;
    }

    /**
     * @return OutputFormatter
     */
    public function output(): OutputFormatter
    {
        return new OutputFormatter(
            $this->dateTime
        );
    }

    /**
     * @return Diff
     */
    public function diff(): Diff
    {
        return new Diff(
            $this->dateTime
        );
    }

    /**
     * @return Modify
     */
    public function modify(): Modify
    {
        return new Modify(
            $this->dateTime
        );
    }

    /**
     * @return DateTime|false
     * @throws Exception
     */
    private function generateNormalisedDate(): DateTime|bool
    {
        $dt = new DateTime();
        return DateTime::createFromFormat(
            'Y-m-d H:i:s',
            sprintf(
                '%s-%s-%s 00:00:00',
                $dt->format('Y'),
                $dt->format('m'),
                $dt->format('d')
            )
        );
    }
}
