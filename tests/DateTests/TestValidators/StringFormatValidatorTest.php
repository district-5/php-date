<?php

namespace District5Tests\DateTests\TestValidators;

use District5\Date\Date;
use PHPUnit\Framework\TestCase;

/**
 * Class StringFormatValidatorTest
 * @package District5Tests\DateTests\TestValidators
 */
class StringFormatValidatorTest extends TestCase
{
    public function testValidYmdFormatWithDashesFor4CharacterYear2Month2DayWithHyphenSeparator()
    {
        $this->assertTrue(
            Date::validateString('2019-10-01')->isYmd(true, true, true, true, '-')
        );
        $this->assertTrue(
            Date::validateString('2019-02-28')->isYmd(true, true, true, true, '-')
        );
        $this->assertTrue(
            Date::validateString('19-10-01')->isYmd(true, false, true, true, '-')
        );
        $this->assertTrue(
            Date::validateString('19-02-28')->isYmd(true, false, true, true, '-')
        );
    }

    public function testValidYmdFormatWithDashesFor4CharacterYear2Month2DayWithSlashSeparator()
    {
        $this->assertTrue(
            Date::validateString('2019/10/01')->isYmd(true, true, true, true, '/')
        );
        $this->assertTrue(
            Date::validateString('2019/02/28')->isYmd(true, true, true, true, '/')
        );
        $this->assertTrue(
            Date::validateString('19/10/01')->isYmd(true, false, true, true, '/')
        );
        $this->assertTrue(
            Date::validateString('19/02/28')->isYmd(true, false, true, true, '/')
        );
    }

    public function testValidTimeFormats()
    {
        $this->assertTrue(
            Date::validateString('01:05')->isTwentyFourHourTimeString()
        );
        $this->assertTrue(
            Date::validateString('23:05')->isTwentyFourHourTimeString()
        );

        $this->assertTrue(
            Date::validateString('01:05:59')->isTwentyFourHourTimeString()
        );
        $this->assertTrue(
            Date::validateString('23:05:32')->isTwentyFourHourTimeString()
        );
    }

    public function testInvalidTimeFormats()
    {
        $this->assertFalse(
            Date::validateString('aa:bb')->isTwentyFourHourTimeString()
        );
        $this->assertFalse(
            Date::validateString('99:05')->isTwentyFourHourTimeString()
        );

        $this->assertFalse(
            Date::validateString('01:aa:59')->isTwentyFourHourTimeString()
        );
        $this->assertFalse(
            Date::validateString('23:05:99')->isTwentyFourHourTimeString()
        );
    }

    public function testInvalidYmdFormatWithDashesFor4CharacterYear2Month2Day()
    {
        $this->assertFalse(
            Date::validateString('2019-02-29')->isYmd(true, true, true, true)
        );
        $this->assertFalse(
            Date::validateString('201-02-29')->isYmd(true, true, true, true)
        );
    }

    public function testValidYmdFormatWithoutDashesFor4CharacterYear2Month2Day()
    {
        $this->assertTrue(
            Date::validateString('20191001')->isYmd(false, true, true, true)
        );
        $this->assertTrue(
            Date::validateString('20190228')->isYmd(false, true, true, true)
        );
    }

    public function testInvalidYmdFormatWithoutDashesFor4CharacterYear2Month2Day()
    {
        $this->assertFalse(
            Date::validateString('20190229')->isYmd(false, true, true, true)
        );
        $this->assertFalse(
            Date::validateString('2010229')->isYmd(false, true, true, true)
        );
    }

    public function testValidYmdFormatWithDashesFor2CharacterYear2Month2Day()
    {
        $this->assertTrue(
            Date::validateString('19-10-01')->isYmd(true, false, true, true)
        );
        $this->assertTrue(
            Date::validateString('19-02-28')->isYmd(true, false, true, true)
        );
    }

    public function testInvalidYmdFormatWithDashesFor2CharacterYear2Month2Day()
    {
        $this->assertFalse(
            Date::validateString('19-02-29')->isYmd(true, true, true, true)
        );
        $this->assertFalse(
            Date::validateString('1-02-29')->isYmd(true, true, true, true)
        );
    }

    public function testValidYmdFormatWithoutDashesFor2CharacterYear2Month2Day()
    {
        $this->assertTrue(
            Date::validateString('191001')->isYmd(false, false, true, true)
        );
        $this->assertTrue(
            Date::validateString('190228')->isYmd(false, false, true, true)
        );
    }

    public function testInvalidYmdFormatWithoutDashesFor2CharacterYear2Month2Day()
    {
        $this->assertFalse(
            Date::validateString('190229')->isYmd(false, false, true, true)
        );
        $this->assertFalse(
            Date::validateString('10229')->isYmd(false, false, true, true)
        );
    }

    public function testValidYmdFormatWithDashesFor2CharacterYear1Month2Day()
    {
        $this->assertTrue(
            Date::validateString('19-9-01')->isYmd(true, false, false, true)
        );
        $this->assertTrue(
            Date::validateString('19-2-28')->isYmd(true, false, false, true)
        );
    }

    public function testInvalidYmdFormatWithDashesFor2CharacterYear1Month2Day()
    {
        $this->assertFalse(
            Date::validateString('19-2-29')->isYmd(true, false, false, true)
        );
        $this->assertFalse(
            Date::validateString('1-2-29')->isYmd(true, false, false, true)
        );
    }

    public function testValidYmdFormatWithDashesFor2CharacterYear2Month1Day()
    {
        $this->assertTrue(
            Date::validateString('19-10-1')->isYmd(true, false, true, false)
        );
        $this->assertTrue(
            Date::validateString('19-02-3')->isYmd(true, false, true, false)
        );
    }

    public function testInvalidYmdFormatWithDashesFor2CharacterYear2Month1Day()
    {
        $this->assertFalse(
            Date::validateString('19-02-0')->isYmd(true, true, true, false)
        );
        $this->assertFalse(
            Date::validateString('1-02-2')->isYmd(true, true, true, false)
        );
    }

    public function testValidYmdFormatWithDashesForShortDateVersion()
    {
        $this->assertTrue(
            Date::validateString('19-10-1')->isYmd(true, false, false, false)
        );
        $this->assertTrue(
            Date::validateString('19-9-3')->isYmd(true, false, false, false)
        );
    }

    public function testInvalidYmdFormatWithoutDashesForShortDateVersion()
    {
        $this->assertFalse(
            Date::validateString('19-0-3')->isYmd(true, false, false, false)
        );
        $this->assertFalse(
            Date::validateString('1-2-2')->isYmd(true, false, false, false)
        );
    }
}
