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

namespace District5Tests\DateTests\TestFormatters;

use DateTime;
use DateTimeZone;
use District5\Date\Date;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Class OutputFormatterEdgeCaseTest
 * @package District5Tests\DateTests\TestFormatters
 */
class OutputFormatterEdgeCaseTest extends TestCase
{
    private static function make(string $value, string $tz = 'UTC'): DateTime
    {
        return DateTime::createFromFormat('Y-m-d H:i:s.u', $value, new DateTimeZone($tz));
    }

    public static function timeOfDayProvider(): array
    {
        return [
            'midnight' => ['2024-01-01 00:00:00.000000', '00:00', '00:00:00', 0, 0, 0, '12:00 AM'],
            'one second past midnight' => ['2024-01-01 00:00:01.000000', '00:00', '00:00:01', 0, 0, 1, '12:00 AM'],
            'single digits padded' => ['2024-01-01 01:02:03.000000', '01:02', '01:02:03', 1, 2, 3, '1:02 AM'],
            'noon' => ['2024-01-01 12:00:00.000000', '12:00', '12:00:00', 12, 0, 0, '12:00 PM'],
            'one pm' => ['2024-01-01 13:05:09.000000', '13:05', '13:05:09', 13, 5, 9, '1:05 PM'],
            'last second of day' => ['2024-01-01 23:59:59.999999', '23:59', '23:59:59', 23, 59, 59, '11:59 PM'],
        ];
    }

    #[DataProvider('timeOfDayProvider')]
    public function testTimeOfDayFormatting(
        string $value,
        string $hm,
        string $hms,
        int $hour,
        int $minute,
        int $second,
        string $twelveHour
    ): void {
        $out = Date::output(self::make($value));
        $this->assertSame($hm, $out->toTimeHM());
        $this->assertSame($hm, $out->getHourMinutes());
        $this->assertSame($hms, $out->toTimeHMS());
        $this->assertSame($hms, $out->getHourMinutesSeconds());
        $this->assertSame($hour, $out->getHour());
        $this->assertSame($minute, $out->getMinutes());
        $this->assertSame($second, $out->getSeconds());
        $this->assertSame($twelveHour, $out->toFormat('g:i A'));
    }

    public function testCustomTimeSeparators(): void
    {
        $out = Date::output(self::make('2024-01-01 07:08:09.000000'));
        $this->assertSame('07.08.09', $out->toTimeHMS('.'));
        $this->assertSame('07-08', $out->toTimeHM('-'));
        $this->assertSame('070809', $out->toTimeHMS(''));
        $this->assertSame('0708', $out->toTimeHM(''));
    }

    public function testDateOutputsWithPaddingAndSeparators(): void
    {
        $out = Date::output(self::make('2024-03-09 07:08:09.000000'));
        $this->assertSame('2024-03-09', $out->toYMD());
        $this->assertSame('2024/03/09', $out->toYMD(true, '/'));
        $this->assertSame('20240309', $out->toYMD(false));
        // When separators are disabled the custom separator is ignored.
        $this->assertSame('20240309', $out->toYMD(false, '/'));

        $this->assertSame('09-03-2024', $out->toDMY());
        $this->assertSame('09.03.2024', $out->toDMY(true, '.'));
        $this->assertSame('09032024', $out->toDMY(false, '.'));

        $this->assertSame('03-09-2024', $out->toMDY());
        $this->assertSame('03/09/2024', $out->toMDY(true, '/'));
        $this->assertSame('03092024', $out->toMDY(false, '/'));

        $this->assertSame('2024-03-09 07:08:09', $out->toYMDHIS());
        $this->assertSame('2024/03/09 07.08.09', $out->toYMDHIS('/', '.'));
        $this->assertSame('20240309 070809', $out->toYMDHIS('', ''));

        $this->assertSame(2024, $out->getYear());
        $this->assertSame(3, $out->getMonth());
        $this->assertSame(9, $out->getDay());
    }

    public function testFourDigitYearPaddingForSmallYears(): void
    {
        $out = Date::output(self::make('0099-01-02 00:00:00.000000'));
        $this->assertSame('0099-01-02', $out->toYMD());
        $this->assertSame(99, $out->getYear());
    }

    public function testTimestampOutputs(): void
    {
        $out = Date::output(self::make('2023-11-14 22:13:20.123456'));
        $this->assertSame(1700000000, $out->toUnixTimestamp());
        $this->assertSame('1700000000', $out->toUnixTimestamp(true));
        $this->assertSame(1700000000, $out->toTimestamp());
        $this->assertSame('1700000000', $out->toTimestamp(true));

        $this->assertSame('1700000000.123456', $out->toMicrosecondTimestamp(true));
        $this->assertSame(1700000000.123456, $out->toMicrosecondTimestamp());
        $this->assertIsFloat($out->toMicrosecondTimestamp());

        $this->assertSame('1700000000.123', $out->toMillisecondTimestamp(true));
        $this->assertSame(1700000000.123, $out->toMillisecondTimestamp());
        $this->assertIsFloat($out->toMillisecondTimestamp());

        $this->assertSame(123456, $out->getMicroseconds());
        $this->assertSame(123, $out->getMilliseconds());
    }

    public function testTimestampOutputsAreTimezoneIndependent(): void
    {
        $utc = Date::output(self::make('2024-01-01 12:00:00.000000'));
        $tokyo = Date::output(self::make('2024-01-01 21:00:00.000000', 'Asia/Tokyo'));
        $this->assertSame($utc->toUnixTimestamp(), $tokyo->toUnixTimestamp());
        $this->assertSame('2024-01-01T12:00:00+00:00', $utc->toISO8601());
        $this->assertSame('2024-01-01T21:00:00+09:00', $tokyo->toISO8601());
        // Calendar parts are reported in the DateTime's own timezone.
        $this->assertSame(21, $tokyo->getHour());
    }

    public function testPreEpochTimestamps(): void
    {
        $out = Date::output(self::make('1969-12-31 23:59:59.000000'));
        $this->assertSame(-1, $out->toUnixTimestamp());
        $this->assertSame('-1', $out->toUnixTimestamp(true));
        $this->assertSame('-1.000000', $out->toMicrosecondTimestamp(true));
        $this->assertSame(-1.0, $out->toMicrosecondTimestamp());
        $this->assertSame('-1.000', $out->toMillisecondTimestamp(true));
        $this->assertSame(-1.0, $out->toMillisecondTimestamp());
    }

    public static function preEpochFractionalProvider(): array
    {
        // [datetime, micro string, micro float, milli string, milli float]
        return [
            'half second before epoch' => ['1969-12-31 23:59:59.500000', '-0.500000', -0.5, '-0.500', -0.5],
            'one and a half seconds before' => ['1969-12-31 23:59:58.500000', '-1.500000', -1.5, '-1.500', -1.5],
            'one microsecond before epoch' => ['1969-12-31 23:59:59.999999', '-0.000001', -0.000001, '-0.001', -0.001],
            'epoch' => ['1970-01-01 00:00:00.000000', '0.000000', 0.0, '0.000', 0.0],
            'just after epoch' => ['1970-01-01 00:00:00.000001', '0.000001', 0.000001, '0.000', 0.0],
        ];
    }

    #[DataProvider('preEpochFractionalProvider')]
    public function testFractionalTimestampsAroundEpochAreSignedDecimals(
        string $value,
        string $micro,
        float $microFloat,
        string $milli,
        float $milliFloat
    ): void {
        $out = Date::output(self::make($value));
        $this->assertSame($micro, $out->toMicrosecondTimestamp(true));
        $this->assertSame($microFloat, $out->toMicrosecondTimestamp());
        $this->assertSame($milli, $out->toMillisecondTimestamp(true));
        $this->assertSame($milliFloat, $out->toMillisecondTimestamp());
    }

    public function testFractionalTimestampsRoundTripThroughInputFormatter(): void
    {
        foreach (['1969-12-31 23:59:59.500000', '1969-01-01 00:00:00.000001', '2023-11-14 22:13:20.123456'] as $value) {
            $dt = self::make($value);
            $micro = Date::output($dt)->toMicrosecondTimestamp(true);
            $this->assertEquals($dt, Date::input($micro)->fromMicrosecondTimestamp(), $value);
        }
        $dt = self::make('1969-12-31 23:59:59.250000');
        $this->assertEquals($dt, Date::input(Date::output($dt)->toMillisecondTimestamp(true))->fromMillisecondTimestamp());
    }

    public function testMillisecondsAreTruncatedNotRounded(): void
    {
        $out = Date::output(self::make('2023-11-14 22:13:20.123999'));
        $this->assertSame(123, $out->getMilliseconds());
        $this->assertSame('1700000000.123', $out->toMillisecondTimestamp(true));
    }

    public function testLetterSeparatorsAreOutputLiterally(): void
    {
        $out = Date::output(self::make('2024-03-09 07:08:09.000000'));
        $this->assertSame('07h08', $out->toTimeHM('h'));
        $this->assertSame('07h08h09', $out->toTimeHMS('h'));
        $this->assertSame('2024d03d09', $out->toYMD(true, 'd'));
        $this->assertSame('09Y03Y2024', $out->toDMY(true, 'Y'));
        $this->assertSame('03\\09\\2024', $out->toMDY(true, '\\'));
        $this->assertSame('2024 of 03 of 09 07at08at09', $out->toYMDHIS(' of ', 'at'));
    }

    public static function daySuffixProvider(): array
    {
        return [
            [1, 'st'], [2, 'nd'], [3, 'rd'], [4, 'th'],
            [11, 'th'], [12, 'th'], [13, 'th'],
            [21, 'st'], [22, 'nd'], [23, 'rd'], [24, 'th'],
            [30, 'th'], [31, 'st'],
        ];
    }

    #[DataProvider('daySuffixProvider')]
    public function testDaySuffix(int $day, string $suffix): void
    {
        $out = Date::output(self::make(sprintf('2024-01-%02d 00:00:00.000000', $day)));
        $this->assertSame($suffix, $out->getDaySuffix());
    }

    public static function isoWeekProvider(): array
    {
        return [
            // 2021-01-01 is a Friday in ISO week 53 of 2020.
            ['2021-01-01', 53, 5, 'Friday', 'January'],
            // 2024-12-30 is a Monday in ISO week 1 of 2025.
            ['2024-12-30', 1, 1, 'Monday', 'December'],
            ['2024-02-29', 9, 4, 'Thursday', 'February'],
            ['2023-01-01', 52, 7, 'Sunday', 'January'],
        ];
    }

    #[DataProvider('isoWeekProvider')]
    public function testIsoWeekAndDayBoundaries(string $date, int $week, int $dow, string $dayName, string $monthName): void
    {
        $out = Date::output(self::make($date . ' 00:00:00.000000'));
        $this->assertSame($week, $out->getWeek());
        $this->assertSame($dow, $out->getDayOfWeek());
        $this->assertSame($dayName, $out->getDayName());
        $this->assertSame($monthName, $out->getMonthName());
    }

    public function testOutputDoesNotMutateOrTrackSourceDateTime(): void
    {
        $dt = self::make('2024-01-01 00:00:00.000000');
        $out = Date::output($dt);
        $dt->modify('+1 day');
        $this->assertSame('2024-01-01', $out->toYMD());
        $this->assertSame('2024-01-02', $dt->format('Y-m-d'));
    }
}
