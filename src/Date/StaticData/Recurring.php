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

namespace District5\Date\StaticData;

use DateTime;
use District5\Date\Manipulators\RecurringDateManipulator;
use District5\Date\Date;

/**
 * Class Recurring
 * @package Date\StaticData
 */
class Recurring
{
    /**
     * Christmas is an annual festival commemorating the birth of Jesus Christ,
     * observed primarily on December 25 as a religious and cultural
     * celebration among billions of people around the world.
     * - Wikipedia
     *
     * @return RecurringDateManipulator
     */
    public function christmasDay(): RecurringDateManipulator
    {
        return $this->simpleDate('%s-12-25 00:00:00');
    }

    /**
     * Star Wars Day, May 4, celebrates George Lucas' Star Wars. It is observed
     * by fans of the media franchise. Observance of the commemorative day
     * spread quickly through media and grassroots celebrations. The date was
     * chosen for the pun on the catchphrase "May the Force be with you" as
     * "May the Fourth be with you".
     * - Wikipedia
     *
     * @return RecurringDateManipulator
     */
    public function starWarsDay(): RecurringDateManipulator
    {
        return $this->simpleDate('%s-05-04 00:00:00');
    }

    /**
     * Christmas Eve is the evening or entire day before Christmas Day, the
     * festival commemorating the birth of Jesus. Christmas Day is observed
     * around the world, and Christmas Eve is widely observed as a full or
     * partial holiday in anticipation of Christmas Day.
     * - Wikipedia
     *
     * @return RecurringDateManipulator
     */
    public function christmasEve(): RecurringDateManipulator
    {
        return $this->simpleDate('%s-12-24 00:00:00');
    }

    /**
     * Boxing Day is a holiday celebrated the day after Christmas Day, thus
     * being the second day of Christmastide. It originated in the United
     * Kingdom and is celebrated in a number of countries that previously
     * formed part of the British Empire.
     * - Wikipedia
     *
     * @return RecurringDateManipulator
     */
    public function boxingDay(): RecurringDateManipulator
    {
        return $this->simpleDate('%s-12-26 00:00:00');
    }

    /**
     * In the Gregorian calendar, New Year's Eve, the last day of the year, is
     * on 31 December. In many countries, New Year's Eve is celebrated at
     * evening parties, where many people dance, eat, drink, and watch or light
     * fireworks. Some Christians attend a watchnight service.
     * - Wikipedia
     *
     * @return RecurringDateManipulator
     */
    public function newYearsEve(): RecurringDateManipulator
    {
        return $this->simpleDate('%s-12-31 00:00:00');
    }

    /**
     * New Year's Day, also simply called New Year, is observed on 1 January,
     * the first day of the year on the modern Gregorian calendar as well as
     * the Julian calendar. In pre-Christian Rome under the Julian calendar,
     * the day was dedicated to Janus, god of gateways and beginnings, for whom
     * January is also named.
     * - Wikipedia
     *
     * @return RecurringDateManipulator
     */
    public function newYearsDay(): RecurringDateManipulator
    {
        return $this->simpleDate('%s-01-01 00:00:00');
    }

    /**
     * Valentine's Day, also called Saint Valentine's Day or the Feast of Saint
     * Valentine, is celebrated annually on February 14.
     * - Wikipedia
     *
     * @return RecurringDateManipulator
     */
    public function valentinesDay(): RecurringDateManipulator
    {
        return $this->simpleDate('%s-02-14 00:00:00');
    }

    /**
     * Dydd Santes Dwynwen is considered to be the Welsh equivalent to
     * Valentine's Day and is celebrated on 25th of January every year.
     * It celebrates Dwynwen, the Welsh saint of lovers.
     * - Wikipedia
     *
     * @return RecurringDateManipulator
     */
    public function stDwynwensDay(): RecurringDateManipulator
    {
        return $this->simpleDate('%s-01-25 00:00:00');
    }

    /**
     * Halloween or Hallowe'en, also known as Allhalloween, All Hallows' Eve,
     * or All Saints' Eve, is a celebration observed in several countries on
     * 31 October, the eve of the Western Christian feast of All Hallows' Day.
     * - Wikipedia
     *
     * @return RecurringDateManipulator
     */
    public function halloween(): RecurringDateManipulator
    {
        return $this->simpleDate('%s-10-31 00:00:00');
    }

    /**
     * Saint David's Day is the feast day of Saint David, the patron saint of
     * Wales, and falls on 1 March, the date of Saint David's death in 589 AD.
     * The feast has been regularly celebrated since the canonisation of David
     * in the 12th century, though it is not a national holiday in the UK.
     * - Wikipedia
     *
     * @return RecurringDateManipulator
     */
    public function stDavidsDay(): RecurringDateManipulator
    {
        return $this->simpleDate('%s-03-01 00:00:00');
    }

    /**
     * Saint Patrick's Day, or the Feast of Saint Patrick, is a cultural and
     * religious celebration held on 17 March, the traditional death date of
     * Saint Patrick, the foremost patron saint of Ireland.
     * - Wikipedia
     *
     * @return RecurringDateManipulator
     */
    public function stPatricksDay(): RecurringDateManipulator
    {
        return $this->simpleDate('%s-03-17 00:00:00');
    }

    /**
     * Saint George's Day, also known as the Feast of Saint George, is the
     * feast day of Saint George as celebrated by various Christian Churches
     * and by the several nations, kingdoms, countries, and cities of which
     * Saint George is the patron saint including England, and regions of
     * Portugal and Spain.
     * - Wikipedia
     *
     * @return RecurringDateManipulator
     */
    public function stGeorgesDay(): RecurringDateManipulator
    {
        return $this->simpleDate('%s-04-23 00:00:00');
    }

    /**
     * April Fools' Day or April Fool's Day is an annual custom on April 1,
     * consisting of practical jokes and hoaxes. The player of the joke or
     * hoax often exposes their action later by shouting "April fools" at the
     * recipient. The recipients of these actions are called April fools.
     * - Wikipedia
     *
     * @return RecurringDateManipulator
     */
    public function aprilFoolsDay(): RecurringDateManipulator
    {
        return $this->simpleDate('%s-04-01 00:00:00');
    }

    /**
     * Guy Fawkes Night, also known as Guy Fawkes Day, Bonfire Night and
     * Firework Night, is an annual commemoration observed on 5 November,
     * primarily in the United Kingdom.
     * - Wikipedia
     *
     * @return RecurringDateManipulator
     */
    public function guyFawkesDay(): RecurringDateManipulator
    {
        return $this->simpleDate('%s-11-05 00:00:00'); // Should never be forgot.
    }

    /**
     * Remembrance Day is a memorial day observed in Commonwealth member
     * states since the end of the First World War to remember the members of
     * their armed forces who have died in the line of duty.
     * - Wikipedia
     *
     * @return RecurringDateManipulator
     */
    public function remembranceDay(): RecurringDateManipulator
    {
        return $this->simpleDate('%s-11-11 00:00:00');
    }

    /**
     * @param string $pattern
     * @return RecurringDateManipulator
     */
    protected function simpleDate(string $pattern): RecurringDateManipulator
    {
        $dt = Date::now()->utc();
        return new RecurringDateManipulator(
            DateTime::createFromFormat(
                'Y-m-d H:i:s',
                sprintf($pattern, $dt->format('Y'))
            )
        );
    }
}
