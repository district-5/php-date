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

namespace District5Tests\DateTests\TestCalculators;

use DateTime;
use District5\Date\Calculators\NtpServer;
use District5\Date\Date;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Class NtpServerOfflineTest
 *
 * Exercises NtpServer without contacting any real NTP server. The server selection logic is tested via a
 * subclass that stubs readNtpServer(); the real socket code is only pointed at local / unparseable hosts.
 *
 * @package District5Tests\DateTests\TestCalculators
 */
class NtpServerOfflineTest extends TestCase
{
    /**
     * @param array<string, int|null> $responses server => timestamp returned (null = failure)
     */
    private function stubbedServer(array $responses): NtpServer
    {
        return new class($responses) extends NtpServer {
            public array $calls = [];

            public function __construct(private readonly array $responses)
            {
            }

            protected function readNtpServer(string $server, int $secondsTimeout = 1, float $millisecondTimeout = 0): int|null
            {
                $this->calls[] = [$server, $secondsTimeout, $millisecondTimeout];
                return $this->responses[$server] ?? null;
            }
        };
    }

    public function testFirstSuccessfulServerWinsAndLaterServersAreNotQueried()
    {
        $ntp = $this->stubbedServer(['a' => null, 'b' => 1700000000, 'c' => 1800000000]);
        $this->assertSame(1700000000, $ntp->getTimestamp(['a', 'b', 'c']));
        $this->assertSame(['a', 'b'], array_column($ntp->calls, 0));
    }

    public function testAllServersFailingReturnsNull()
    {
        $ntp = $this->stubbedServer([]);
        $this->assertNull($ntp->getTimestamp(['a', 'b', 'c']));
        $this->assertSame(['a', 'b', 'c'], array_column($ntp->calls, 0));
        $this->assertNull($ntp->getObject(['a']));
    }

    public function testEmptyServerListReturnsNull()
    {
        $ntp = $this->stubbedServer(['a' => 1700000000]);
        $this->assertNull($ntp->getTimestamp([]));
        $this->assertNull($ntp->getObject([]));
        $this->assertSame([], $ntp->calls);
    }

    public function testNullServersUsesTheDefaultList()
    {
        $ntp = $this->stubbedServer([]);
        $this->assertNull($ntp->getTimestamp());
        $this->assertSame(NtpServer::$servers, array_column($ntp->calls, 0));
        $this->assertContains('time.cloudflare.com', NtpServer::$servers);
        $this->assertSame(array_values(array_unique(NtpServer::$servers)), NtpServer::$servers);
    }

    public function testTimeoutsArePassedThrough()
    {
        $ntp = $this->stubbedServer([]);
        $ntp->getTimestamp(['a'], 3, 250.0);
        $ntp->getObject(['b'], 2, 10.0);
        $this->assertSame([['a', 3, 250.0], ['b', 2, 10.0]], $ntp->calls);
    }

    public function testGetObjectReturnsUtcDateTimeForTimestamp()
    {
        $ntp = $this->stubbedServer(['a' => null, 'b' => 1709208000]);
        $obj = $ntp->getObject(['a', 'b']);
        $this->assertInstanceOf(DateTime::class, $obj);
        $this->assertSame(1709208000, $obj->getTimestamp());
        $this->assertSame('2024-02-29 12:00:00', $obj->format('Y-m-d H:i:s'));
        $this->assertSame('+00:00', $obj->getTimezone()->getName());
    }

    public function testUnparseableHostReturnsNull()
    {
        // An empty host fails inside stream_socket_client() before any lookup or packet is sent.
        $start = microtime(true);
        $this->assertNull(Date::ntpServer()->getTimestamp([''], 1, 0));
        $this->assertNull(Date::ntpServer()->getObject([''], 1, 0));
        $this->assertLessThan(1.0, microtime(true) - $start);
    }

    public function testLocalhostWithoutNtpServiceReturnsNull()
    {
        // Nothing normally listens on UDP 127.0.0.1:123, so the read is refused locally (no external network).
        $start = microtime(true);
        $timestamp = Date::ntpServer()->getTimestamp(['127.0.0.1'], 1, 0);
        $this->assertLessThan(2.0, microtime(true) - $start);
        if ($timestamp !== null) {
            // A local NTP daemon answered; it must still be a sane current time.
            $this->assertEqualsWithDelta(time(), $timestamp, 60);
            return;
        }
        $this->assertNull($timestamp);
    }

    /**
     * Exposes the protected static helpers for direct testing.
     */
    private function helpers(): NtpServer
    {
        return new class extends NtpServer {
            public static function address(string $server): ?string
            {
                return static::buildAddress($server);
            }

            public static function parse(string $response, string $token, float $sentAt, float $receivedAt): ?float
            {
                return static::parseResponse($response, $token, $sentAt, $receivedAt);
            }

            public static function request(string $token): string
            {
                return static::buildRequest($token);
            }

            public static function toUnix(string $bytes): ?float
            {
                return static::ntpTimestampToUnix($bytes);
            }
        };
    }

    private static function ntpBytes(float $unix): string
    {
        $seconds = (int)floor($unix);
        $fraction = (int)round(($unix - $seconds) * 4294967296);
        return pack('NN', ($seconds + 2208988800) & 0xFFFFFFFF, $fraction);
    }

    /**
     * Build a server response packet.
     */
    private static function packet(
        string $token,
        float $receive,
        float $transmit,
        int $leap = 0,
        int $version = 4,
        int $mode = 4,
        int $stratum = 2
    ): string {
        return chr(($leap << 6) | ($version << 3) | $mode) . chr($stratum) . chr(6) . chr(0xEC)
            . str_repeat("\0", 8) . 'TEST'
            . self::ntpBytes($transmit - 10)
            . $token
            . self::ntpBytes($receive)
            . self::ntpBytes($transmit);
    }

    public static function addressProvider(): array
    {
        return [
            'hostname' => ['time.cloudflare.com', 'udp://time.cloudflare.com:123'],
            'hostname with port' => ['ntp.example.com:1123', 'udp://ntp.example.com:1123'],
            'trimmed' => ['  pool.ntp.org ', 'udp://pool.ntp.org:123'],
            'ipv4' => ['162.159.200.1', 'udp://162.159.200.1:123'],
            'ipv4 with port' => ['127.0.0.1:5123', 'udp://127.0.0.1:5123'],
            'ipv6' => ['2606:4700:f1::1', 'udp://[2606:4700:f1::1]:123'],
            'bracketed ipv6 with port' => ['[::1]:5123', 'udp://[::1]:5123'],
            'bracketed ipv6' => ['[::1]', 'udp://[::1]:123'],
            'empty' => ['', null],
            'whitespace' => ['   ', null],
            'port 0' => ['example.com:0', null],
            'port too high' => ['example.com:65536', null],
            'scheme' => ['udp://example.com', null],
            'path' => ['example.com/x', null],
            'space inside' => ['exa mple.com', null],
            'bad bracketed' => ['[not-an-ip]:123', null],
        ];
    }

    #[DataProvider('addressProvider')]
    public function testBuildAddress(string $server, ?string $expected)
    {
        $this->assertSame($expected, $this->helpers()::address($server));
    }

    public function testBuildRequestIsVersion4ClientPacketCarryingToken()
    {
        $request = $this->helpers()::request('ABCDEFGH');
        $this->assertSame(48, strlen($request));
        $this->assertSame(0b00100011, ord($request[0])); // LI=0, VN=4, Mode=3 (client)
        $this->assertSame(str_repeat("\0", 39), substr($request, 1, 39));
        $this->assertSame('ABCDEFGH', substr($request, 40, 8));
    }

    public function testValidResponseIsCorrectedForRoundTripDelay()
    {
        // Client sends at 1000.0 and receives at 1000.2 (200ms round trip, server processing 0).
        // The server's clock is exactly 5s ahead: it receives and transmits at 1005.1.
        $token = 'abcdefgh';
        $response = self::packet($token, 1700001005.1, 1700001005.1);
        $time = $this->helpers()::parse($response, $token, 1700001000.0, 1700001000.2);
        $this->assertEqualsWithDelta(1700001005.2, $time, 0.0001);
    }

    public function testServerProcessingTimeIsExcludedFromDelay()
    {
        // 300ms round trip of which the server held the packet for 100ms; server clock 2s behind.
        $token = 'abcdefgh';
        $response = self::packet($token, 1700000998.1, 1700000998.2);
        $time = $this->helpers()::parse($response, $token, 1700001000.0, 1700001000.3);
        $this->assertEqualsWithDelta(1700000998.3, $time, 0.0001);
    }

    public function testEmptyReceiveTimestampFallsBackToTransmit()
    {
        $token = 'abcdefgh';
        $response = self::packet($token, 0, 1700000000.5);
        $response = substr($response, 0, 32) . str_repeat("\0", 8) . substr($response, 40);
        $time = $this->helpers()::parse($response, $token, 1700000000.0, 1700000001.0);
        $this->assertEqualsWithDelta(1700000001.0, $time, 0.0001);
    }

    public static function invalidResponseProvider(): array
    {
        $token = 'abcdefgh';
        $now = 1700000000.0;
        return [
            'empty' => ['', $token],
            'short packet' => [substr(self::packet($token, $now, $now), 0, 47), $token],
            'client mode' => [self::packet($token, $now, $now, mode: 3), $token],
            'broadcast mode' => [self::packet($token, $now, $now, mode: 5), $token],
            'version 2' => [self::packet($token, $now, $now, version: 2), $token],
            'version 5' => [self::packet($token, $now, $now, version: 5), $token],
            'unsynchronised leap indicator' => [self::packet($token, $now, $now, leap: 3), $token],
            'kiss-o-death stratum 0' => [self::packet($token, $now, $now, stratum: 0), $token],
            'unsynchronised stratum 16' => [self::packet($token, $now, $now, stratum: 16), $token],
            'origin does not echo token' => [self::packet('zzzzzzzz', $now, $now), $token],
            'zero transmit timestamp' => [
                substr(self::packet($token, $now, $now), 0, 40) . str_repeat("\0", 8),
                $token,
            ],
            'transmit before receive' => [self::packet($token, $now + 5, $now), $token],
            'server processing longer than the round trip' => [self::packet($token, $now, $now + 5), $token],
        ];
    }

    #[DataProvider('invalidResponseProvider')]
    public function testInvalidResponsesAreRejected(string $response, string $token)
    {
        $this->assertNull($this->helpers()::parse($response, $token, 1700000000.0, 1700000000.1));
    }

    public function testLeapIndicatorWarningsAreAccepted()
    {
        $token = 'abcdefgh';
        foreach ([1, 2] as $leap) {
            $response = self::packet($token, 1700000000.0, 1700000000.0, leap: $leap);
            $this->assertNotNull($this->helpers()::parse($response, $token, 1700000000.0, 1700000000.0));
        }
    }

    public function testVersion3ResponsesAreAccepted()
    {
        $token = 'abcdefgh';
        $response = self::packet($token, 1700000000.0, 1700000000.0, version: 3);
        $this->assertNotNull($this->helpers()::parse($response, $token, 1700000000.0, 1700000000.0));
    }

    public function testNtpTimestampConversionAcrossThe2036EraRollover()
    {
        $helpers = $this->helpers();
        $this->assertNull($helpers::toUnix(str_repeat("\0", 8)));
        $this->assertNull($helpers::toUnix('short'));
        $this->assertSame(0.0, $helpers::toUnix(pack('NN', 2208988800, 0)));
        $this->assertSame(1700000000.5, $helpers::toUnix(pack('NN', 1700000000 + 2208988800, 0x80000000)));
        // Last second of NTP era 0 is 2036-02-07 06:28:15 UTC, and the counter then wraps to 0.
        $this->assertSame(2085978495.0, $helpers::toUnix(pack('NN', 0xFFFFFFFF, 0)));
        $this->assertSame(2085978496.0, $helpers::toUnix(pack('NN', 0x00000000, 1)) - (1 / 4294967296));
        $this->assertSame(2085978496.0 + 3600, $helpers::toUnix(pack('NN', 3600, 0)));
    }

    public function testReadNtpServerRoundsDownToWholeSeconds()
    {
        $ntp = new class extends NtpServer {
            protected function readNtpServerMicrotime(string $server, int $secondsTimeout = 1, float $millisecondTimeout = 0): ?float
            {
                return $server === 'a' ? 1700000000.999 : null;
            }
        };
        $this->assertSame(1700000000, $ntp->getTimestamp(['b', 'a']));
        $this->assertSame(1700000000.999, $ntp->getMicrotime(['b', 'a']));
        $this->assertNull($ntp->getMicrotime(['b']));
        $this->assertNull($ntp->getMicrotime([]));
    }

    public function testNonStringServersAreSkipped()
    {
        $ntp = $this->stubbedServer(['a' => 1700000000]);
        $this->assertSame(1700000000, $ntp->getTimestamp([null, 123, 'a']));
        $this->assertSame(['a'], array_column($ntp->calls, 0));
    }

    /**
     * Start the local fixture responder and return [process, pipes, port].
     */
    private function startResponder(string $behaviour): array
    {
        if (!function_exists('proc_open')) {
            $this->markTestSkipped('proc_open is not available.');
        }
        $process = proc_open(
            [PHP_BINARY, __DIR__ . '/fixtures/ntp_responder.php', $behaviour],
            [1 => ['pipe', 'w'], 2 => ['pipe', 'w']],
            $pipes
        );
        $this->assertIsResource($process);
        $port = trim((string)fgets($pipes[1]));
        if (!ctype_digit($port)) {
            proc_terminate($process);
            $this->markTestSkipped('Local UDP responder could not start: ' . stream_get_contents($pipes[2]));
        }
        return [$process, $pipes, intval($port)];
    }

    private function stopResponder(array $responder): void
    {
        [$process, $pipes] = $responder;
        foreach ($pipes as $pipe) {
            fclose($pipe);
        }
        proc_terminate($process);
        proc_close($process);
    }

    public function testRealSocketAgainstLocalResponder()
    {
        $responder = $this->startResponder('valid');
        try {
            $time = Date::ntpServer()->getMicrotime(['127.0.0.1:' . $responder[2]], 2);
            $this->assertNotNull($time);
            $this->assertEqualsWithDelta(microtime(true), $time, 1.0);
        } finally {
            $this->stopResponder($responder);
        }
    }

    public static function badResponderProvider(): array
    {
        return [
            'kiss-o-death' => ['kod'],
            'wrong token' => ['wrong-token'],
            'short packet' => ['short'],
        ];
    }

    #[DataProvider('badResponderProvider')]
    public function testRealSocketRejectsBadResponses(string $behaviour)
    {
        $responder = $this->startResponder($behaviour);
        try {
            $this->assertNull(Date::ntpServer()->getTimestamp(['127.0.0.1:' . $responder[2]], 2));
        } finally {
            $this->stopResponder($responder);
        }
    }

    public function testRealSocketTimesOutOnSilentServer()
    {
        $responder = $this->startResponder('silent');
        try {
            $start = microtime(true);
            $this->assertNull(Date::ntpServer()->getTimestamp(['127.0.0.1:' . $responder[2]], 0, 300));
            $elapsed = microtime(true) - $start;
            $this->assertGreaterThanOrEqual(0.25, $elapsed);
            $this->assertLessThan(1.5, $elapsed);
        } finally {
            $this->stopResponder($responder);
        }
    }

    public function testFallsBackToNextServerAfterBadResponse()
    {
        $bad = $this->startResponder('kod');
        $good = $this->startResponder('valid');
        try {
            $time = Date::ntpServer()->getTimestamp(['127.0.0.1:' . $bad[2], '127.0.0.1:' . $good[2]], 2);
            $this->assertNotNull($time);
            $this->assertEqualsWithDelta(time(), $time, 2);
        } finally {
            $this->stopResponder($bad);
            $this->stopResponder($good);
        }
    }
}
