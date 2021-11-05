<?php

namespace District5Tests\DateTests\TestTz;

use District5\Date\Date;
use District5\Date\Tz\TzConstants;
use PHPUnit\Framework\TestCase;

/**
 * Class TimezoneTest
 * @package District5Tests\DateTests\TestTz
 */
class TimezoneTest extends TestCase
{
    public function testConvertToTimezone()
    {
        $dt = Date::now()->utc();

        $toLondon = Date::timezone($dt)->toTimezone(TzConstants::EUROPE_LONDON);
        $toLosAngeles = Date::timezone($toLondon)->toTimezone(TzConstants::AMERICA_LOS_ANGELES);
        $backToLondon = Date::timezone($toLosAngeles)->toTimezone(TzConstants::EUROPE_LONDON);

        $backToUtc = Date::timezone($backToLondon)->toTimezone(TzConstants::UTC);

        // Check first utc equals last utc
        $this->assertEquals($backToUtc->format('Y-m-d H:i:s'), $dt->format('Y-m-d H:i:s'));

        // Check first london equals last london
        $this->assertEquals($backToLondon->format('Y-m-d H:i:s'), $toLondon->format('Y-m-d H:i:s'));
    }

    public function testSwitchBetweenTimezones()
    {
        $dt = Date::now()->utc();

        $toLondon = Date::timezone($dt)->toTimezoneFromTimezone(
            TzConstants::EUROPE_LONDON,
            TzConstants::UTC
        );

        $toLosAngeles = Date::timezone($toLondon)->toTimezoneFromTimezone(
            TzConstants::AMERICA_LOS_ANGELES,
            TzConstants::EUROPE_LONDON
        );
        $backToLondon = Date::timezone($toLosAngeles)->toTimezoneFromTimezone(
            TzConstants::EUROPE_LONDON,
            TzConstants::AMERICA_LOS_ANGELES
        );

        $backToUtc = Date::timezone($backToLondon)->toTimezoneFromTimezone(
            TzConstants::UTC,
            TzConstants::EUROPE_LONDON
        );

        // Check first utc equals last utc
        $this->assertEquals($backToUtc->format('Y-m-d H:i:s'), $dt->format('Y-m-d H:i:s'));

        // Check first london equals last london
        $this->assertEquals($backToLondon->format('Y-m-d H:i:s'), $toLondon->format('Y-m-d H:i:s'));
    }

    public function testSwitchBetweenTimezonesWithoutDeclaration()
    {
        $dt = Date::now()->utc();

        $toLondon = Date::timezone($dt)->toTimezoneFromTimezone(
            TzConstants::EUROPE_LONDON,
            TzConstants::UTC
        );

        $toLosAngeles = Date::timezone($toLondon)->toTimezoneFromTimezone(
            TzConstants::AMERICA_LOS_ANGELES
        );
        $backToLondon = Date::timezone($toLosAngeles)->toTimezoneFromTimezone(
            TzConstants::EUROPE_LONDON
        );

        $backToUtc = Date::timezone($backToLondon)->toTimezoneFromTimezone(
            TzConstants::UTC
        );

        // Check first utc equals last utc
        $this->assertEquals($backToUtc->format('Y-m-d H:i:s'), $dt->format('Y-m-d H:i:s'));

        // Check first london equals last london
        $this->assertEquals($backToLondon->format('Y-m-d H:i:s'), $toLondon->format('Y-m-d H:i:s'));
    }
}
