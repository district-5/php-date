<?php
namespace District5\Date\StaticData;

use DateTime;
use District5\Date\Date;

/**
 * Class Month
 * @package District5\Date\StaticData
 */
class Month
{
    /**
     * @return DateTime
     */
    public function firstDateInCurrentMonth(): DateTime
    {
        $now = Date::nowDefault();
        return self::firstDateInMonthFromDateTime($now);
    }

    /**
     * @param int $monthNumber
     * @param int|null $year
     * @return DateTime
     */
    public function firstDateInMonth(int $monthNumber, ?int $year = null): DateTime
    {
        if ($year === null) {
            $year = Date::output(Date::nowDefault())->getYear();
        }
        return Date::createYMDHISM($year, $monthNumber, 1);
    }

    /**
     * @param DateTime $provided
     * @return DateTime
     */
    public function firstDateInMonthFromDateTime(DateTime $provided): DateTime
    {
        $tmp = Date::modify($provided)->setDate(1);
        return Date::modify($tmp)->setTime(0, 0);
    }

    /**
     * @return DateTime
     */
    public function lastDateInCurrentMonth(): DateTime
    {
        $now = Date::nowDefault();
        $output = Date::output($now);
        return Date::createYMDHISM(
            $output->getYear(),
            $output->getMonth(),
            1,
            23,
            59,
            59,
            999999
        );
    }

    /**
     * @param int $monthNumber
     * @param int|null $year
     * @return DateTime
     */
    public function lastDateInMonth(int $monthNumber, ?int $year = null): DateTime
    {
        if ($year === null) {
            $year = Date::output(Date::nowDefault())->getYear();
        }
        $date = Date::createYMDHISM($year, $monthNumber, 1);
        $nextMonth = Date::modify($date)->plus()->months(1);
        return Date::modify($nextMonth)->minus()->days(1);
    }

    /**
     * @param DateTime $provided
     * @return DateTime
     */
    public function lastDateInMonthFromDateTime(DateTime $provided): DateTime
    {
        $output = Date::output($provided);
        return self::lastDateInMonth(
            $output->getMonth(),
            $output->getYear()
        );
    }

    /**
     * @param int $monthNumber
     * @param int|null $year
     * @return int
     */
    public function numberDaysInMonth(int $monthNumber, ?int $year = null): int
    {
        return Date::output(
            self::lastDateInMonth(
                $monthNumber,
                $year
            )
        )->getDay();
    }

    /**
     * @param DateTime $provided
     * @return int
     */
    public function numberDaysInMonthFromDateTime(DateTime $provided): int
    {
        return Date::output(
            self::lastDateInMonthFromDateTime($provided)
        )->getDay();
    }
}
