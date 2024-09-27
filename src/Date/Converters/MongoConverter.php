<?php /** @noinspection PhpFullyQualifiedNameUsageInspection */

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

namespace District5\Date\Converters;

use DateTime;
use District5\Date\Date;

/**
 * Class MongoConverter
 *
 * The references to Mongo classes are purposely hidden to avoid any runtime errors if the driver is not installed.
 *
 * @package District5\Date\Converters
 */
class MongoConverter
{
    /**
     * Convert a DateTime to a UTCDateTime object.
     *
     * @param DateTime $dateTime
     * @return \MongoDB\BSON\UTCDateTime|false
     * @noinspection PhpComposerExtensionStubsInspection
     */
    public function convertTo(DateTime $dateTime): \MongoDB\BSON\UTCDateTime|bool
    {
        $cl = '\MongoDB\BSON\UTCDateTime';
        if (class_exists($cl) === false) {
            return false;
        }

        return new $cl((Date::output($dateTime)->toMillisecondTimestamp() * 1000));
    }

    /**
     * Convert a UTCDateTime to a DateTime object.
     *
     * @param \MongoDB\BSON\UTCDateTime $provided
     * @return DateTime|false
     * @noinspection PhpComposerExtensionStubsInspection
     */
    public function convertFrom(\MongoDB\BSON\UTCDateTime $provided): DateTime|bool
    {
        return $provided->toDateTime();
    }

    /**
     * Convert a DateTime to a Mongo ObjectId.
     *
     * @param DateTime $dateTime
     * @return \MongoDB\BSON\ObjectId|false
     * @noinspection PhpComposerExtensionStubsInspection
     */
    public function toObjectId(DateTime $dateTime): \MongoDB\BSON\ObjectId|bool
    {
        $cl = '\MongoDB\BSON\ObjectId';
        if (class_exists($cl) === false) {
            return false;
        }

        $ts = strtotime($dateTime->format('Y-m-d H:i:s'));
        if ($ts === false) {
            return false;
        }
        $ts = str_pad(dechex($ts), 8, '0', STR_PAD_LEFT);

        return new $cl($ts . '0000000000000000');
    }

    /**
     * Convert a Mongo ObjectId to a DateTime object.
     *
     * @param \MongoDB\BSON\ObjectId $objectId
     * @return DateTime|false
     * @noinspection PhpComposerExtensionStubsInspection
     */
    public function fromObjectId(\MongoDB\BSON\ObjectId $objectId): DateTime|bool
    {
        return Date::input($objectId->getTimestamp())->fromTimestamp();
    }
}
