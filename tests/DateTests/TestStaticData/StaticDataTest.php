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

namespace District5Tests\DateTests\TestStaticData;

use DateTime;
use District5\Date\Date;
use PHPUnit\Framework\TestCase;

/**
 * Class StaticDataTest
 * @package District5Tests\DateTests\TestStaticData
 */
class StaticDataTest extends TestCase
{
    public function testThisYear()
    {
        $dt = new DateTime();
        $this->assertEquals(
            intval($dt->format('Y')) . '-12-26 00:00:00',
            Date::recurring()->boxingDay()->thisYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            intval($dt->format('Y')) . '-12-25 00:00:00',
            Date::recurring()->christmasDay()->thisYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            intval($dt->format('Y')) . '-12-24 00:00:00',
            Date::recurring()->christmasEve()->thisYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            intval($dt->format('Y')) . '-10-31 00:00:00',
            Date::recurring()->halloween()->thisYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            intval($dt->format('Y')) . '-01-01 00:00:00',
            Date::recurring()->newYearsDay()->thisYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            intval($dt->format('Y')) . '-12-31 00:00:00',
            Date::recurring()->newYearsEve()->thisYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            intval($dt->format('Y')) . '-02-14 00:00:00',
            Date::recurring()->valentinesDay()->thisYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            intval($dt->format('Y')) . '-05-04 00:00:00',
            Date::recurring()->starWarsDay()->thisYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            intval($dt->format('Y')) . '-03-01 00:00:00',
            Date::recurring()->stDavidsDay()->thisYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            intval($dt->format('Y')) . '-03-17 00:00:00',
            Date::recurring()->stPatricksDay()->thisYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            intval($dt->format('Y')) . '-04-01 00:00:00',
            Date::recurring()->aprilFoolsDay()->thisYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            intval($dt->format('Y')) . '-11-05 00:00:00',
            Date::recurring()->guyFawkesDay()->thisYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            intval($dt->format('Y')) . '-11-11 00:00:00',
            Date::recurring()->remembranceDay()->thisYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            intval($dt->format('Y')) . '-04-23 00:00:00',
            Date::recurring()->stGeorgesDay()->thisYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            intval($dt->format('Y')) . '-01-25 00:00:00',
            Date::recurring()->stDwynwensDay()->thisYear()->format('Y-m-d H:i:s')
        );
    }

    public function testLastYear()
    {
        $dt = new DateTime();
        $this->assertEquals(
            (intval($dt->format('Y')) - 1) . '-12-26 00:00:00',
            Date::recurring()->boxingDay()->lastYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            (intval($dt->format('Y')) - 1) . '-12-25 00:00:00',
            Date::recurring()->christmasDay()->lastYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            (intval($dt->format('Y')) - 1) . '-12-24 00:00:00',
            Date::recurring()->christmasEve()->lastYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            (intval($dt->format('Y')) - 1) . '-10-31 00:00:00',
            Date::recurring()->halloween()->lastYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            (intval($dt->format('Y')) - 1) . '-01-01 00:00:00',
            Date::recurring()->newYearsDay()->lastYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            (intval($dt->format('Y')) - 1) . '-12-31 00:00:00',
            Date::recurring()->newYearsEve()->lastYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            (intval($dt->format('Y')) - 1) . '-02-14 00:00:00',
            Date::recurring()->valentinesDay()->lastYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            (intval($dt->format('Y')) - 1) . '-05-04 00:00:00',
            Date::recurring()->starWarsDay()->lastYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            (intval($dt->format('Y')) - 1) . '-03-01 00:00:00',
            Date::recurring()->stDavidsDay()->lastYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            (intval($dt->format('Y')) - 1) . '-03-17 00:00:00',
            Date::recurring()->stPatricksDay()->lastYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            (intval($dt->format('Y')) - 1) . '-04-01 00:00:00',
            Date::recurring()->aprilFoolsDay()->lastYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            (intval($dt->format('Y')) - 1) . '-11-05 00:00:00',
            Date::recurring()->guyFawkesDay()->lastYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            (intval($dt->format('Y')) - 1) . '-11-11 00:00:00',
            Date::recurring()->remembranceDay()->lastYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            (intval($dt->format('Y')) - 1) . '-04-23 00:00:00',
            Date::recurring()->stGeorgesDay()->lastYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            (intval($dt->format('Y')) - 1) . '-01-25 00:00:00',
            Date::recurring()->stDwynwensDay()->lastYear()->format('Y-m-d H:i:s')
        );
    }

    public function testNextYear()
    {
        $dt = new DateTime();
        $this->assertEquals(
            (intval($dt->format('Y')) + 1) . '-12-26 00:00:00',
            Date::recurring()->boxingDay()->nextYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            (intval($dt->format('Y')) + 1) . '-12-25 00:00:00',
            Date::recurring()->christmasDay()->nextYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            (intval($dt->format('Y')) + 1) . '-12-24 00:00:00',
            Date::recurring()->christmasEve()->nextYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            (intval($dt->format('Y')) + 1) . '-10-31 00:00:00',
            Date::recurring()->halloween()->nextYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            (intval($dt->format('Y')) + 1) . '-01-01 00:00:00',
            Date::recurring()->newYearsDay()->nextYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            (intval($dt->format('Y')) + 1) . '-12-31 00:00:00',
            Date::recurring()->newYearsEve()->nextYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            (intval($dt->format('Y')) + 1) . '-02-14 00:00:00',
            Date::recurring()->valentinesDay()->nextYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            (intval($dt->format('Y')) + 1) . '-05-04 00:00:00',
            Date::recurring()->starWarsDay()->nextYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            (intval($dt->format('Y')) + 1) . '-03-01 00:00:00',
            Date::recurring()->stDavidsDay()->nextYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            (intval($dt->format('Y')) + 1) . '-03-17 00:00:00',
            Date::recurring()->stPatricksDay()->nextYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            (intval($dt->format('Y')) + 1) . '-04-01 00:00:00',
            Date::recurring()->aprilFoolsDay()->nextYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            (intval($dt->format('Y')) + 1) . '-11-05 00:00:00',
            Date::recurring()->guyFawkesDay()->nextYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            (intval($dt->format('Y')) + 1) . '-11-11 00:00:00',
            Date::recurring()->remembranceDay()->nextYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            (intval($dt->format('Y')) + 1) . '-04-23 00:00:00',
            Date::recurring()->stGeorgesDay()->nextYear()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            (intval($dt->format('Y')) + 1) . '-01-25 00:00:00',
            Date::recurring()->stDwynwensDay()->nextYear()->format('Y-m-d H:i:s')
        );
    }
}
