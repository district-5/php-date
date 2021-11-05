<?php

namespace District5\Date\Validators;

use DateTime;
use Exception;

/**
 * Class StringFormatValidator
 * @package District5\Date\Validators
 */
class StringFormatValidator
{
    /**
     * @var string|int|float
     */
    protected $input;

    /**
     * OutputFormatter constructor.
     * @param string|int|float $input
     */
    public function __construct($input)
    {
        $this->input = $input;
    }

    /**
     * Validate a string of the following formats are valid date representation. The separator control is provided
     * by the `$hasSeparator` parameter and the final `$separator` parameter, which dictates which character is used as
     * a separator.
     *
     * 1) YYYY-MM-DD
     * 2) YYYYMMDD
     * 3) YY-MM-DD
     * 4) YYMMDD
     * 5) YY-M-D
     * 6) YYMD
     *
     * @param bool $hasSeparator - Whether dashes are expected in the string.
     * @param bool $fourCharacterYear - true is 'YYYY' format, false is 'YY' format
     * @param bool $twoCharacterMonth - true is 'MM' format, false is 'M' format
     * @param bool $twoCharacterDay - true is 'DD' format, false is 'D' format
     * @param string $separator - '-' the separator of the string.
     * @return bool
     */
    public function isYmd(bool $hasSeparator = true, bool $fourCharacterYear = true, bool $twoCharacterMonth = true, bool $twoCharacterDay = true, string $separator = '-'): bool
    {
        try {
            $format = '';
            if ($fourCharacterYear) {
                $format .= 'Y' . $separator;
            } else {
                $format .= 'y' . $separator;
            }
            if ($twoCharacterMonth) {
                $format .= 'm' . $separator;
            } else {
                $format .= 'n' . $separator;
            }
            if ($twoCharacterDay) {
                $format .= 'd';
            } else {
                $format .= 'j';
            }

            if (!$hasSeparator) {
                $format = str_replace($separator, '', $format);
            }
            $dt = DateTime::createFromFormat($format, $this->input);
            if (!$dt) {
                return false;
            }
            return $dt->format($format) === $this->input;
        } catch (Exception $e) {
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isTwentyFourHourTimeString(): bool
    {
        $given = $this->input;
        $totalLength = strlen($given);
        if ($totalLength !== 5 && $totalLength !== 8 || !ctype_digit(str_replace(':', '', $given))) {
            return false;
        }
        $dt = false;
        if ($totalLength === 5) {
            $dt = DateTime::createFromFormat('Y-m-d H:i:s', sprintf('2020-01-01 %s:00', $given));
        } elseif ($totalLength === 8) {
            $dt = DateTime::createFromFormat('Y-m-d H:i:s', sprintf('2020-01-01 %s', $given));
        }

        if ($dt === false) {
            return false;
        }
        if ($totalLength === 5) {
            return $dt->format('H:i') === $this->input;
        } elseif ($totalLength === 8) {
            return $dt->format('H:i:s') === $this->input;
        }
        return false;
    }
}
