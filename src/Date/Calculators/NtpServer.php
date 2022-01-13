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

namespace District5\Date\Calculators;

use DateTime;
use DateTimeZone;

/**
 * Class NtpServer
 * @package District5\Date\Calculators
 */
class NtpServer
{
    /**
     * @var string[]
     */
    public static $servers = [
        'time1.google.com',
        'time2.google.com',
        'time3.google.com',
        'time4.google.com'
    ];

    /**
     * Get a DateTime object from a timestamp provided by an NTP server. The DateTime is always in UTC.
     *
     * @param array|null $servers
     * @param int $secondsTimeout
     * @param float|int $millisecondTimeout
     * @return DateTime|null
     */
    public function getObject(array $servers = null, int $secondsTimeout = 1, float $millisecondTimeout = 0): ?DateTime
    {
        $time = self::getTimestamp($servers, $secondsTimeout, $millisecondTimeout);
        if ($time === null) {
            return null;
        }
        return DateTime::createFromFormat('U', strval($time), new DateTimeZone('UTC'));
    }

    /**
     * Get the time from an NTP server. The timestamp returned is always in UTC.
     *
     * @link https://en.wikipedia.org/wiki/Network_Time_Protocol
     * @param array|null $servers
     * @param int $secondsTimeout
     * @param float $millisecondTimeout
     * @return int|null
     */
    public function getTimestamp(array $servers = null, int $secondsTimeout = 1, float $millisecondTimeout = 0): ?int
    {
        if (null === $servers) {
            $servers = self::$servers;
        }

        $time = null;
        foreach ($servers as $server) {
            $time = $this->readNtpServer($server, $secondsTimeout, $millisecondTimeout);
            if ($time !== null) {
                break;
            }
        }

        return $time;
    }

    /**
     * Attempt to get the time from an NTP server.
     *
     * @param string $server
     * @param int $secondsTimeout
     * @param float $millisecondTimeout
     * @return int|mixed|null
     */
    protected function readNtpServer(string $server, int $secondsTimeout = 1, float $millisecondTimeout = 0)
    {
        $socket = @stream_socket_client(
            sprintf('udp://%s:123', $server),
            $errorCode,
            $errorMessage,
            $millisecondTimeout
        );
        if (false === $socket || $errorCode !== 0) {
            return null;
        }
        @stream_set_timeout($socket, $secondsTimeout, $millisecondTimeout);
        if (false === @fwrite($socket, "\010\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0")) {
            return null;
        }

        if (false === $binary = @fread($socket, 48)) {
            return null;
        }

        @socket_close($socket);
        if (false === $data = @unpack('N12', $binary)) {
            return null;
        }
        return $data[9] - 2208988800; // Unix timestamp is Jan 1, 1970
    }
}
