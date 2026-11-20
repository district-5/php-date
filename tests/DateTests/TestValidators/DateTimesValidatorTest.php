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

use DateTime;
use DateTimeImmutable;
use District5\Date\Date;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Class DateTimesValidatorTest
 * @package District5Tests\DateTests\TestValidators
 */
class DateTimesValidatorTest extends TestCase
{
    public function testEmptyArrayIsValid(): void
    {
        $this->assertTrue(Date::validateArray([])->isArrayOfDateTimes());
    }

    public function testArrayOfDateTimesIsValid(): void
    {
        $this->assertTrue(Date::validateArray([new DateTime('2024-01-01')])->isArrayOfDateTimes());
        $this->assertTrue(Date::validateArray([
            'a' => new DateTime('2024-01-01'),
            'b' => new DateTime('2024-01-02', new \DateTimeZone('Asia/Tokyo')),
        ])->isArrayOfDateTimes());
    }

    public function testDateTimeSubclassIsValid(): void
    {
        $sub = new class('2024-01-01') extends DateTime {
        };
        $this->assertTrue(Date::validateArray([$sub])->isArrayOfDateTimes());
    }

    public static function invalidMemberProvider(): array
    {
        return [
            'null' => [null],
            'string date' => ['2024-01-01'],
            'int timestamp' => [1704067200],
            'float' => [1.5],
            'bool' => [false],
            'array' => [[]],
            'stdClass' => [new stdClass()],
            'DateTimeImmutable' => [new DateTimeImmutable('2024-01-01')],
        ];
    }

    #[DataProvider('invalidMemberProvider')]
    public function testAnyInvalidMemberMakesArrayInvalid(mixed $invalid): void
    {
        $this->assertFalse(Date::validateArray([$invalid])->isArrayOfDateTimes());
        $this->assertFalse(Date::validateArray([new DateTime('2024-01-01'), $invalid])->isArrayOfDateTimes());
        $this->assertFalse(Date::validateArray([$invalid, new DateTime('2024-01-01')])->isArrayOfDateTimes());
    }
}
