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

namespace District5Tests\DateTests\TestValidators;

use District5\Date\Date;
use District5\Date\Validators\StringFormatValidator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Class StringFormatValidatorEdgeCaseTest
 * @package District5Tests\DateTests\TestValidators
 */
class StringFormatValidatorEdgeCaseTest extends TestCase
{
    public static function ymdProvider(): array
    {
        // [input, hasSeparator, fourCharYear, twoCharMonth, twoCharDay, separator, expected]
        return [
            'leap day' => ['2024-02-29', true, true, true, true, '-', true],
            'leap day 2000' => ['2000-02-29', true, true, true, true, '-', true],
            'feb 29 non leap' => ['2023-02-29', true, true, true, true, '-', false],
            'feb 29 1900' => ['1900-02-29', true, true, true, true, '-', false],
            'feb 30' => ['2024-02-30', true, true, true, true, '-', false],
            'april 31' => ['2024-04-31', true, true, true, true, '-', false],
            'month 13' => ['2024-13-01', true, true, true, true, '-', false],
            'month 00' => ['2024-00-01', true, true, true, true, '-', false],
            'day 00' => ['2024-01-00', true, true, true, true, '-', false],
            'day 32' => ['2024-01-32', true, true, true, true, '-', false],
            'leading whitespace' => [' 2024-01-01', true, true, true, true, '-', false],
            'trailing whitespace' => ['2024-01-01 ', true, true, true, true, '-', false],
            'empty' => ['', true, true, true, true, '-', false],
            'non padded when padded expected' => ['2024-1-1', true, true, true, true, '-', false],
            'wrong separator' => ['2024/01/01', true, true, true, true, '-', false],
            'custom dot separator' => ['2024.02.09', true, true, true, true, '.', true],
            'hyphen given when dot expected' => ['2024-02-09', true, true, true, true, '.', false],
            'separator present but not expected' => ['2024-02-09', false, true, true, true, '-', false],
            'two digit year with 4 expected' => ['24-02-09', true, true, true, true, '-', false],
            'four digit year with 2 expected' => ['2024-02-09', true, false, true, true, '-', false],
            'short year, short month and day' => ['24-2-9', true, false, false, false, '-', true],
            'short year, short month, two digit day' => ['24-2-09', true, false, false, true, '-', true],
            'short month given padded' => ['24-02-9', true, false, false, false, '-', false],
            'short month feb 30' => ['24-2-30', true, false, false, false, '-', false],
            'no separators full' => ['20240229', false, true, true, true, '-', true],
            'no separators invalid' => ['20230229', false, true, true, true, '-', false],
            'no separators short year' => ['240229', false, false, true, true, '-', true],
        ];
    }

    #[DataProvider('ymdProvider')]
    public function testIsYmd(
        string $input,
        bool $hasSeparator,
        bool $fourCharacterYear,
        bool $twoCharacterMonth,
        bool $twoCharacterDay,
        string $separator,
        bool $expected
    ): void {
        $this->assertSame(
            $expected,
            Date::validateString($input)->isYmd(
                $hasSeparator,
                $fourCharacterYear,
                $twoCharacterMonth,
                $twoCharacterDay,
                $separator
            )
        );
    }

    public function testIsYmdWithIntegerInput(): void
    {
        $this->assertTrue((new StringFormatValidator(20240229))->isYmd(false));
        $this->assertTrue((new StringFormatValidator(240229))->isYmd(false, false));
        $this->assertFalse((new StringFormatValidator(20230229))->isYmd(false));
        $this->assertFalse((new StringFormatValidator(2024022))->isYmd(false));
    }

    public static function literalSeparatorProvider(): array
    {
        // Separators that are DateTime format characters must be matched literally.
        return [
            'd separator' => ['2024d02d09', 'd', true],
            'Y separator' => ['2024Y02Y09', 'Y', true],
            'multi char separator' => ['2024 - 02 - 09', ' - ', true],
            'letter separator missing from input' => ['20240209', 'd', false],
        ];
    }

    #[DataProvider('literalSeparatorProvider')]
    public function testIsYmdSeparatorIsLiteral(string $input, string $separator, bool $expected): void
    {
        $this->assertSame($expected, Date::validateString($input)->isYmd(true, true, true, true, $separator));
    }

    public function testIsYmdWithoutSeparatorIgnoresLetterSeparator(): void
    {
        // Previously str_replace() stripped 'd' from the format itself when $hasSeparator was false.
        $this->assertTrue(Date::validateString('20240209')->isYmd(false, true, true, true, 'd'));
    }

    public static function twentyFourHourProvider(): array
    {
        return [
            'midnight hm' => ['00:00', true],
            'noon hm' => ['12:00', true],
            'end of day hm' => ['23:59', true],
            'midnight hms' => ['00:00:00', true],
            'end of day hms' => ['23:59:59', true],
            'hour 24 hm' => ['24:00', false],
            'hour 24 hms' => ['24:00:00', false],
            'hour 25' => ['25:00', false],
            'minute 60' => ['23:60', false],
            'second 60' => ['23:59:60', false],
            'unpadded hour' => ['7:30', false],
            'unpadded minute' => ['07:3', false],
            'unpadded hour hms' => ['7:30:00', false],
            'no colon five digits' => ['12345', false],
            'no colon four digits' => ['1230', false],
            'misplaced colon' => ['1:234', false],
            'colons only' => ['::::', false],
            'spaces' => ['  :  ', false],
            'negative' => ['-1:00', false],
            'too many parts' => ['1:2:3:4', false],
            'leading whitespace' => [' 12:00', false],
            'trailing whitespace' => ['12:00 ', false],
            'am pm suffix' => ['12:00 PM', false],
            'dot separator' => ['12.00', false],
            'empty' => ['', false],
            'with fraction' => ['12:00:00.5', false],
        ];
    }

    #[DataProvider('twentyFourHourProvider')]
    public function testIsTwentyFourHourTimeString(string $input, bool $expected): void
    {
        $this->assertSame($expected, Date::validateString($input)->isTwentyFourHourTimeString());
    }
}
