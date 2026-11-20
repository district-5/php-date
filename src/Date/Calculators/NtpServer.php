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
 * Query the time from public NTP (Network Time Protocol) servers using a single SNTP request per server.
 *
 * Servers are tried in order until one returns a valid response. Every response is validated (packet size,
 * mode, stratum, leap indicator, kiss-o'-death and that it echoes the random token sent in the request), and the
 * result is corrected for network round-trip delay.
 *
 * Note: each server is given the full timeout, so the worst case is (number of servers x timeout). DNS lookups
 * are performed by PHP before the timeout applies and are not covered by it.
 *
 * @link https://datatracker.ietf.org/doc/html/rfc5905
 * @link https://datatracker.ietf.org/doc/html/rfc4330
 * @package District5\Date\Calculators
 */
class NtpServer
{
    /**
     * Seconds between the NTP epoch (1900-01-01) and the Unix epoch (1970-01-01).
     */
    protected const NTP_UNIX_OFFSET = 2208988800;

    /**
     * Seconds in one NTP era (the 32-bit seconds field rolls over every 2^32 seconds, next in February 2036).
     */
    protected const NTP_ERA_SECONDS = 4294967296;

    /**
     * Size of an NTP packet without extension fields.
     */
    protected const PACKET_SIZE = 48;

    /**
     * Default NTP port.
     */
    protected const DEFAULT_PORT = 123;

    /**
     * Public NTP servers, tried in order. Servers may be given as "host", "host:port", an IPv4/IPv6 address,
     * or "[ipv6]:port".
     *
     * Google and Amazon "smear" leap seconds across a day, other providers step them. Outside a leap-second
     * smear window they agree to well within a second, which is the precision getTimestamp() returns.
     *
     * @var string[]
     */
    public static array $servers = [
        'time1.google.com',
        'time2.google.com',
        'time3.google.com',
        'time4.google.com',
        'time.cloudflare.com',
        'time.apple.com',
        'time.aws.com',
        'time.windows.com',
        '0.pool.ntp.org',
        '1.pool.ntp.org',
        '2.pool.ntp.org',
        '3.pool.ntp.org',
        'time.nist.gov',
    ];

    /**
     * Get a DateTime object from a timestamp provided by an NTP server. The DateTime is always in UTC.
     *
     * @param array|null $servers servers to try in order, or null for NtpServer::$servers
     * @param int $secondsTimeout per-server timeout (whole seconds part)
     * @param float $millisecondTimeout per-server timeout (milliseconds part, added to $secondsTimeout)
     * @return DateTime|null null if no server returned a valid response
     */
    public function getObject(?array $servers = null, int $secondsTimeout = 1, float $millisecondTimeout = 0): ?DateTime
    {
        $time = $this->getTimestamp($servers, $secondsTimeout, $millisecondTimeout);
        if ($time === null) {
            return null;
        }
        return DateTime::createFromFormat('U', strval($time), new DateTimeZone('UTC'));
    }

    /**
     * Get the current Unix timestamp (whole seconds, UTC) from an NTP server.
     *
     * @link https://en.wikipedia.org/wiki/Network_Time_Protocol
     * @param array|null $servers servers to try in order, or null for NtpServer::$servers
     * @param int $secondsTimeout per-server timeout (whole seconds part)
     * @param float $millisecondTimeout per-server timeout (milliseconds part, added to $secondsTimeout)
     * @return int|null null if no server returned a valid response
     */
    public function getTimestamp(?array $servers = null, int $secondsTimeout = 1, float $millisecondTimeout = 0): ?int
    {
        foreach ($servers ?? self::$servers as $server) {
            if (!is_string($server)) {
                continue;
            }
            $time = $this->readNtpServer($server, $secondsTimeout, $millisecondTimeout);
            if ($time !== null) {
                return $time;
            }
        }
        return null;
    }

    /**
     * Get the current Unix time from an NTP server with sub-second precision, corrected for network delay.
     *
     * @param array|null $servers servers to try in order, or null for NtpServer::$servers
     * @param int $secondsTimeout per-server timeout (whole seconds part)
     * @param float $millisecondTimeout per-server timeout (milliseconds part, added to $secondsTimeout)
     * @return float|null null if no server returned a valid response
     */
    public function getMicrotime(?array $servers = null, int $secondsTimeout = 1, float $millisecondTimeout = 0): ?float
    {
        foreach ($servers ?? self::$servers as $server) {
            if (!is_string($server)) {
                continue;
            }
            $time = $this->readNtpServerMicrotime($server, $secondsTimeout, $millisecondTimeout);
            if ($time !== null) {
                return $time;
            }
        }
        return null;
    }

    /**
     * Attempt to get the current Unix timestamp (whole seconds) from a single NTP server.
     *
     * @param string $server
     * @param int $secondsTimeout
     * @param float $millisecondTimeout
     * @return int|null
     */
    protected function readNtpServer(string $server, int $secondsTimeout = 1, float $millisecondTimeout = 0): int|null
    {
        $time = $this->readNtpServerMicrotime($server, $secondsTimeout, $millisecondTimeout);
        if ($time === null) {
            return null;
        }
        return intval(floor($time));
    }

    /**
     * Attempt to get the current Unix time, with sub-second precision, from a single NTP server.
     *
     * @param string $server
     * @param int $secondsTimeout
     * @param float $millisecondTimeout
     * @return float|null
     */
    protected function readNtpServerMicrotime(string $server, int $secondsTimeout = 1, float $millisecondTimeout = 0): ?float
    {
        $address = static::buildAddress($server);
        if ($address === null) {
            return null;
        }

        // The timeout is $secondsTimeout plus $millisecondTimeout. A non-positive total falls back to 1 second.
        $timeout = max(0, $secondsTimeout) + (max(0.0, $millisecondTimeout) / 1000);
        if ($timeout <= 0) {
            $timeout = 1.0;
        }
        $wholeSeconds = intval(floor($timeout));
        $microseconds = intval(round(($timeout - $wholeSeconds) * 1_000_000));
        if ($microseconds >= 1_000_000) {
            $wholeSeconds++;
            $microseconds -= 1_000_000;
        }

        $socket = @stream_socket_client($address, $errorCode, $errorMessage, $timeout);
        if ($socket === false) {
            return null;
        }

        try {
            if (false === @stream_set_timeout($socket, $wholeSeconds, $microseconds)) {
                return null;
            }

            // A random transmit timestamp acts as a nonce: a genuine reply must echo it back as its origin
            // timestamp, which rejects stale, misdirected or spoofed packets (RFC 5905 section 8, RFC 9109).
            $token = random_bytes(8);
            $request = static::buildRequest($token);

            $sentAt = microtime(true);
            $written = @fwrite($socket, $request);
            if ($written !== strlen($request)) {
                return null;
            }

            $response = @fread($socket, 1024);
            $receivedAt = microtime(true);
            if (!is_string($response) || $response === '') {
                return null;
            }
            $meta = stream_get_meta_data($socket);
            if (!empty($meta['timed_out'])) {
                return null;
            }

            return static::parseResponse($response, $token, $sentAt, $receivedAt);
        } finally {
            @fclose($socket);
        }
    }

    /**
     * Build a 48-byte SNTP client request (LI=0, VN=4, Mode=3) carrying $token as the transmit timestamp.
     *
     * @param string $token 8 bytes
     * @return string
     */
    protected static function buildRequest(string $token): string
    {
        return chr(0b00100011) . str_repeat("\0", 39) . substr(str_pad($token, 8, "\0"), 0, 8);
    }

    /**
     * Validate an NTP server response and return the Unix time at $receivedAt, corrected for network delay.
     *
     * @param string $response raw packet received from the server
     * @param string $token the 8-byte transmit timestamp sent in the request
     * @param float $sentAt local microtime() when the request was sent
     * @param float $receivedAt local microtime() when the response was received
     * @return float|null null if the response is not a valid, synchronised server reply to our request
     */
    protected static function parseResponse(string $response, string $token, float $sentAt, float $receivedAt): ?float
    {
        if (strlen($response) < self::PACKET_SIZE) {
            return null;
        }

        $first = ord($response[0]);
        $leap = $first >> 6;
        $version = ($first >> 3) & 0b111;
        $mode = $first & 0b111;
        $stratum = ord($response[1]);

        if ($mode !== 4) {
            return null; // Not a server reply.
        }
        if ($version < 3 || $version > 4) {
            return null;
        }
        if ($leap === 3) {
            return null; // Server clock is not synchronised.
        }
        if ($stratum === 0 || $stratum >= 16) {
            return null; // Kiss-o'-death (e.g. RATE / DENY) or unsynchronised.
        }
        if (substr($response, 24, 8) !== $token) {
            return null; // Not a reply to our request.
        }

        $transmit = static::ntpTimestampToUnix(substr($response, 40, 8));
        if ($transmit === null) {
            return null;
        }
        // Some SNTP servers leave the receive timestamp empty; treat processing time as zero in that case.
        $receive = static::ntpTimestampToUnix(substr($response, 32, 8)) ?? $transmit;
        if ($transmit < $receive) {
            return null; // The server cannot send its reply before receiving the request.
        }

        // Clock offset per RFC 5905: ((T2 - T1) + (T3 - T4)) / 2. Server time at T4 is T4 + offset.
        $offset = (($receive - $sentAt) + ($transmit - $receivedAt)) / 2;
        $delay = ($receivedAt - $sentAt) - ($transmit - $receive);
        if ($delay < -1.0) {
            return null; // Server claims to have held the packet for longer than the round trip took.
        }

        return $receivedAt + $offset;
    }

    /**
     * Convert an 8-byte NTP timestamp to Unix time. Handles the 2036 era rollover.
     *
     * @param string $bytes
     * @return float|null null for the zero timestamp (meaning "not set")
     */
    protected static function ntpTimestampToUnix(string $bytes): ?float
    {
        if (strlen($bytes) !== 8 || $bytes === str_repeat("\0", 8)) {
            return null;
        }
        $parts = unpack('Nseconds/Nfraction', $bytes);
        if ($parts === false) {
            return null;
        }
        $seconds = $parts['seconds'];
        // Timestamps with the top bit clear are in NTP era 1 (from 2036-02-07), RFC 4330 section 3.
        if ($seconds < 0x80000000) {
            $seconds += self::NTP_ERA_SECONDS;
        }
        return ($seconds - self::NTP_UNIX_OFFSET) + ($parts['fraction'] / self::NTP_ERA_SECONDS);
    }

    /**
     * Turn a server string into a stream_socket_client() UDP address, or null if it is not valid.
     * Accepts "host", "host:port", "1.2.3.4", "1.2.3.4:port", "2001:db8::1" and "[2001:db8::1]:port".
     *
     * @param string $server
     * @return string|null
     */
    protected static function buildAddress(string $server): ?string
    {
        $server = trim($server);
        if ($server === '') {
            return null;
        }

        $port = self::DEFAULT_PORT;
        if (filter_var($server, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false) {
            $host = '[' . $server . ']';
        } elseif (preg_match('/^\[([0-9A-Fa-f:.]+)](?::(\d{1,5}))?$/', $server, $m)) {
            if (filter_var($m[1], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false) {
                return null;
            }
            $host = '[' . $m[1] . ']';
            $port = isset($m[2]) ? intval($m[2]) : $port;
        } elseif (preg_match('/^([A-Za-z0-9.-]+)(?::(\d{1,5}))?$/', $server, $m)) {
            $host = $m[1];
            $port = isset($m[2]) ? intval($m[2]) : $port;
        } else {
            return null;
        }

        if ($port < 1 || $port > 65535) {
            return null;
        }
        return sprintf('udp://%s:%d', $host, $port);
    }
}
