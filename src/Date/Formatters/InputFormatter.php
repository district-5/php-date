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
use DateTimeInterface;
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
     * between the characters using the `$separator` param. Defaults to '-''
     *
     * @param string $separator default '-' the separator to use.
     * @return DateTime|false
     */
    public function fromYMD(string $separator = '-'): DateTime|bool
    {
        $tmp = DateTime::createFromFormat(
            sprintf('Y%sm%sd', $separator, $separator),
            strval($this->input)
        );
        if ($tmp !== false && $tmp->format(sprintf('Y%sm%sd', $separator, $separator)) === strval($this->input)) {
            return $tmp;
        }
        return false;
    }

    /**
     * Convert a string of DD-MM-YYYY to a DateTime object. Optionally specifying the separator
     * between the characters using the `$separator` param. Defaults to '-'
     *
     * @param string $separator default '-' the separator to use.
     * @return DateTime|false
     */
    public function fromDMY(string $separator = '-'): DateTime|bool
    {
        $tmp = DateTime::createFromFormat(
            sprintf('d%sm%sY', $separator, $separator),
            strval($this->input)
        );
        if ($tmp !== false && $tmp->format(sprintf('d%sm%sY', $separator, $separator)) === strval($this->input)) {
            return $tmp;
        }
        return false;
    }

    /**
     * Convert a string of MM-DD-YYYY Optionally specifying the separator
     * between the characters using the `$separator` param. Defaults to '-'
     *
     * @param string $separator default '-' the separator to use.
     * @return DateTime|false
     */
    public function fromMDY(string $separator = '-'): DateTime|bool
    {
        $tmp = DateTime::createFromFormat(
            sprintf('m%sd%sY', $separator, $separator),
            strval($this->input)
        );
        if ($tmp !== false && $tmp->format(sprintf('m%sd%sY', $separator, $separator)) === strval($this->input)) {
            return $tmp;
        }
        return false;
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
     * @param string $dateSeparator
     * @param string $timeSeparator
     * @return DateTime|false
     */
    public function fromYMDHIS(string $dateSeparator = '-', string $timeSeparator = ':'): DateTime|bool
    {
        return $this->fromFormat(
            sprintf(
                'Y%sm%sd H%si%ss',
                $dateSeparator,
                $dateSeparator,
                $timeSeparator,
                $timeSeparator
            )
        );
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
     * Create a DateTime from a given format.
     *
     * @param string $format
     * @return DateTime|false
     */
    public function fromFormat(string $format): DateTime|bool
    {
        return DateTime::createFromFormat($format, $this->input);
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
        if ($strict === false && str_contains($this->input, '.')) {
            return $this->fromMicrosecondTimestamp();
        }
        $tmp = DateTime::createFromFormat(
            'U',
            strval($this->input)
        );
        if (false !== $tmp && $tmp->format('U') === strval($this->input)) {
            return $tmp;
        }
        return false;
    }

    /**
     * Convert a microsecond timestamp to a DateTime object.
     * If $strict = true, non microsecond timestamps will return false.
     *
     * @param bool $strict (optional) default true.
     * @return DateTime|false
     */
    public function fromMicrosecondTimestamp(bool $strict = true): DateTime|bool
    {
        if ($strict === false && !str_contains($this->input, '.')) {
            return $this->fromUnixTimestamp();
        }
        $tmp = DateTime::createFromFormat(
            'U.u',
            strval($this->input)
        );
        if ($tmp !== false && $tmp->format('U.u') === strval($this->input)) {
            return $tmp;
        }
        return false;
    }

    /**
     * Convert a millisecond timestamp to a DateTime object.
     * If $strict = true, non millisecond timestamps will return false.
     *
     * @param bool $strict (optional) default true.
     * @return DateTime|false
     */
    public function fromMillisecondTimestamp(bool $strict = true): DateTime|bool
    {
        if ($strict === false && !str_contains($this->input, '.')) {
            return $this->fromUnixTimestamp();
        }
        $tmp = DateTime::createFromFormat(
            'U.v',
            strval($this->input)
        );
        if ($tmp !== false && $tmp->format('U.v') === strval($this->input)) {
            return $tmp;
        }
        return false;
    }

    /**
     * Convert an ISO 8601 string to a DateTime object
     *
     * @return DateTime|false
     */
    public function fromISO8601(): DateTime|bool
    {
        $tmp = DateTime::createFromFormat(
            DateTimeInterface::ATOM,
            strval($this->input)
        );
        if ($tmp !== false && $tmp->format(DateTimeInterface::ATOM) === strval($this->input)) {
            return $tmp;
        }
        return false;
    }
}
