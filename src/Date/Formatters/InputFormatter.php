<?php /** @noinspection PhpComposerExtensionStubsInspection */
/** @noinspection PhpFullyQualifiedNameUsageInspection */
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

namespace District5\Date\Formatters;

use DateTime;
use District5\Date\Date;

/**
 * Class InputFormatter
 * @package District5\Date\Formatters
 */
class InputFormatter
{
    /**
     * @var string|int|float
     */
    protected string|int|float $input;

    /**
     * InputFormatter constructor.
     * @param float|int|string $input
     */
    public function __construct(float|int|string $input)
    {
        $this->input = $input;
    }

    /**
     * Convert a string of YYYY-MM-DD to a DateTime object. Optionally specifying the separator
     * between the characters using the `$separator` param. Defaults to '-'. The separator is always
     * treated literally, even if it contains characters that are DateTime format codes.
     *
     * @param string $separator default '-' the separator to use.
     * @return DateTime|false
     */
    public function fromYMD(string $separator = '-'): DateTime|bool
    {
        $sep = self::escapeFormatLiteral($separator);
        return $this->fromStrictFormat(sprintf('Y%sm%sd', $sep, $sep));
    }

    /**
     * Convert a string of DD-MM-YYYY to a DateTime object. Optionally specifying the separator
     * between the characters using the `$separator` param. Defaults to '-'. The separator is always
     * treated literally.
     *
     * @param string $separator default '-' the separator to use.
     * @return DateTime|false
     */
    public function fromDMY(string $separator = '-'): DateTime|bool
    {
        $sep = self::escapeFormatLiteral($separator);
        return $this->fromStrictFormat(sprintf('d%sm%sY', $sep, $sep));
    }

    /**
     * Convert a string of MM-DD-YYYY Optionally specifying the separator
     * between the characters using the `$separator` param. Defaults to '-'. The separator is always
     * treated literally.
     *
     * @param string $separator default '-' the separator to use.
     * @return DateTime|false
     */
    public function fromMDY(string $separator = '-'): DateTime|bool
    {
        $sep = self::escapeFormatLiteral($separator);
        return $this->fromStrictFormat(sprintf('m%sd%sY', $sep, $sep));
    }

    /**
     * @return DateTime|false
     * @see InputFormatter::fromUnixTimestamp()
     */
    public function fromTimestamp(): DateTime|bool
    {
        return $this->fromUnixTimestamp();
    }

    /**
     * Convert a string of YYYY-MM-DD HH:MM:SS to a DateTime object. Values that PHP would otherwise
     * silently roll over (e.g. '2023-02-29 10:00:00' or '2024-01-01 24:00:00') are rejected.
     * Separators are always treated literally.
     *
     * @param string $dateSeparator
     * @param string $timeSeparator
     * @return DateTime|false
     */
    public function fromYMDHIS(string $dateSeparator = '-', string $timeSeparator = ':'): DateTime|bool
    {
        $d = self::escapeFormatLiteral($dateSeparator);
        $t = self::escapeFormatLiteral($timeSeparator);
        return $this->fromStrictFormat(sprintf('Y%sm%sd H%si%ss', $d, $d, $t, $t));
    }

    /**
     * Create a DateTime from a given format.
     *
     * @param \MongoDB\BSON\UTCDateTime $provided
     * @return DateTime|false
     * @noinspection PhpMissingParamTypeInspection
     * @noinspection PhpUnused
     */
    public function fromMongoUtcDateTime($provided): DateTime|bool
    {
        return Date::mongo()->convertFrom($provided);
    }

    /**
     * Create a DateTime from a Mongo ObjectId.
     *
     * @param \MongoDB\BSON\ObjectId $provided
     * @return DateTime|false
     * @noinspection PhpMissingParamTypeInspection
     * @noinspection PhpUnused
     */
    public function fromMongoObjectId($provided): DateTime|bool
    {
        return Date::mongo()->fromObjectId($provided);
    }

    /**
     * Create a DateTime from a given format. This is a thin wrapper around DateTime::createFromFormat() and
     * performs no strictness checks.
     *
     * @param string $format
     * @return DateTime|false
     */
    public function fromFormat(string $format): DateTime|bool
    {
        return DateTime::createFromFormat($format, $this->inputAsString());
    }

    /**
     * Convert a unix timestamp to a DateTime object
     * If $strict = true, anything of that a numeric timestamp will return false.
     * If $strict = false, a timestamp that looks like a microsecond timestamp will be treated as such.
     *
     * @param bool $strict (optional) default true.
     * @return DateTime|false
     */
    public function fromUnixTimestamp(bool $strict = true): DateTime|bool
    {
        $input = $this->inputAsString();
        if ($strict === false && str_contains($input, '.')) {
            return $this->fromMicrosecondTimestamp();
        }
        $tmp = DateTime::createFromFormat('U', $input);
        if (false !== $tmp && $tmp->format('U') === $input) {
            return $tmp;
        }
        return false;
    }

    /**
     * Convert a microsecond timestamp ({seconds}.{6 digit microseconds}) to a DateTime object.
     * Negative values are read as a signed decimal, so '-0.500000' is half a second before the epoch.
     * If $strict = true, non microsecond timestamps will return false.
     *
     * @param bool $strict (optional) default true.
     * @return DateTime|false
     */
    public function fromMicrosecondTimestamp(bool $strict = true): DateTime|bool
    {
        if ($strict === false && !str_contains($this->inputAsString(), '.')) {
            return $this->fromUnixTimestamp();
        }
        $input = $this->inputAsString(6);
        $tmp = self::fromFractionalTimestamp($input, 6);
        if ($tmp !== false && Date::output($tmp)->toMicrosecondTimestamp(true) === $input) {
            return $tmp;
        }
        return false;
    }

    /**
     * Convert a millisecond timestamp ({seconds}.{3 digit milliseconds}) to a DateTime object.
     * Negative values are read as a signed decimal, so '-0.500' is half a second before the epoch.
     * If $strict = true, non millisecond timestamps will return false.
     *
     * @param bool $strict (optional) default true.
     * @return DateTime|false
     */
    public function fromMillisecondTimestamp(bool $strict = true): DateTime|bool
    {
        if ($strict === false && !str_contains($this->inputAsString(), '.')) {
            return $this->fromUnixTimestamp();
        }
        $input = $this->inputAsString(3);
        $tmp = self::fromFractionalTimestamp($input, 3);
        if ($tmp !== false && Date::output($tmp)->toMillisecondTimestamp(true) === $input) {
            return $tmp;
        }
        return false;
    }

    /**
     * Convert an ISO 8601 string to a DateTime object. Accepted forms are
     * YYYY-MM-DDTHH:MM:SS[.fraction](Z|+HH:MM|-HH:MM|+HHMM|-HHMM). The fraction may have 1 to 9 digits;
     * anything beyond microsecond precision is truncated. Invalid calendar or clock values are rejected.
     *
     * @return DateTime|false
     */
    public function fromISO8601(): DateTime|bool
    {
        $pattern = '/^(\d{4}-\d{2}-\d{2})T(\d{2}:\d{2}:\d{2})(?:\.(\d{1,9}))?(?:(Z)|([+-])(\d{2}):?(\d{2}))$/D';
        if (preg_match($pattern, $this->inputAsString(), $m) !== 1) {
            return false;
        }
        $fraction = str_pad(substr($m[3], 0, 6), 6, '0');
        if ($m[4] === 'Z') {
            $offset = '+00:00';
        } else {
            if (intval($m[6]) > 23 || intval($m[7]) > 59) {
                return false;
            }
            $sign = ($m[6] === '00' && $m[7] === '00') ? '+' : $m[5];
            $offset = sprintf('%s%s:%s', $sign, $m[6], $m[7]);
        }
        $normalised = sprintf('%sT%s.%s%s', $m[1], $m[2], $fraction, $offset);
        $format = 'Y-m-d\TH:i:s.uP';
        $tmp = DateTime::createFromFormat($format, $normalised);
        if ($tmp !== false && $tmp->format($format) === $normalised) {
            return $tmp;
        }
        return false;
    }

    /**
     * Parse the input with the given format, rejecting anything that does not round-trip exactly
     * (which catches PHP's silent overflow such as February 30th becoming March 1st/2nd).
     *
     * @param string $format
     * @return DateTime|false
     */
    private function fromStrictFormat(string $format): DateTime|bool
    {
        $input = $this->inputAsString();
        $tmp = DateTime::createFromFormat($format, $input);
        if ($tmp !== false && $tmp->format($format) === $input) {
            return $tmp;
        }
        return false;
    }

    /**
     * Parse '[-]{seconds}.{fraction}' where the fraction has exactly $digits digits, treating the value
     * as a signed decimal number of seconds.
     *
     * @param string $input
     * @param int $digits
     * @return DateTime|false
     */
    private static function fromFractionalTimestamp(string $input, int $digits): DateTime|bool
    {
        if (preg_match('/^(-?)(\d{1,12})\.(\d{' . $digits . '})$/D', $input, $m) !== 1) {
            return false;
        }
        $unit = 10 ** $digits;
        $total = intval($m[2]) * $unit + intval($m[3]);
        if ($m[1] === '-') {
            $total = -$total;
        }
        $seconds = intdiv($total, $unit);
        $remainder = $total % $unit;
        if ($remainder < 0) {
            $remainder += $unit;
            $seconds -= 1;
        }
        return DateTime::createFromFormat(
            'U.u',
            sprintf('%d.%06d', $seconds, $remainder * intdiv(1000000, $unit))
        );
    }

    /**
     * Get the input as a string without losing float precision. Floats are rendered with exactly
     * $decimals decimal places when given, otherwise in their shortest round-trip form.
     *
     * @param int|null $decimals
     * @return string
     */
    private function inputAsString(?int $decimals = null): string
    {
        if (!is_float($this->input)) {
            return strval($this->input);
        }
        if ($decimals !== null) {
            return sprintf('%.' . $decimals . 'F', $this->input);
        }
        $asString = var_export($this->input, true);
        return str_ends_with($asString, '.0') ? substr($asString, 0, -2) : $asString;
    }

    /**
     * Escape every character of a string so that DateTime treats it literally within a format string.
     *
     * @param string $value
     * @return string
     */
    private static function escapeFormatLiteral(string $value): string
    {
        if ($value === '') {
            return '';
        }
        return '\\' . implode('\\', str_split($value));
    }
}
