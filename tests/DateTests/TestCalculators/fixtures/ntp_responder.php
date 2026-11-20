<?php
/**
 * Minimal local NTP responder used by NtpServerOfflineTest. Binds UDP on 127.0.0.1 (random port), prints the port,
 * answers a single request and exits. Usage: php ntp_responder.php <behaviour>
 *
 * Behaviours: valid | kod | wrong-token | short | silent
 */

$behaviour = $argv[1] ?? 'valid';
$server = stream_socket_server('udp://127.0.0.1:0', $errno, $errstr, STREAM_SERVER_BIND);
if ($server === false) {
    fwrite(STDERR, "bind failed: $errstr\n");
    exit(1);
}
$name = stream_socket_get_name($server, false);
echo substr($name, strrpos($name, ':') + 1), "\n";
flush();

stream_set_timeout($server, 5);
$request = stream_socket_recvfrom($server, 1024, 0, $peer);
if (!is_string($request) || strlen($request) < 48 || $behaviour === 'silent') {
    exit(0);
}

$toNtp = static function (float $unix): string {
    $seconds = (int)floor($unix);
    $fraction = (int)(($unix - $seconds) * 4294967296);
    return pack('NN', ($seconds + 2208988800) & 0xFFFFFFFF, $fraction);
};

$now = microtime(true);
$token = substr($request, 40, 8);
if ($behaviour === 'wrong-token') {
    $token = strrev($token) ^ "\xff\xff\xff\xff\xff\xff\xff\xff";
}
$stratum = $behaviour === 'kod' ? 0 : 2;
$response = chr(0b00100100) . chr($stratum) . chr(6) . chr(0xEC) // LI=0 VN=4 Mode=4, stratum, poll, precision
    . str_repeat("\0", 8)        // root delay + dispersion
    . 'TEST'                     // reference id (or kiss code)
    . $toNtp($now - 10)          // reference timestamp
    . $token                     // origin timestamp (echo of our transmit timestamp)
    . $toNtp($now)               // receive timestamp
    . $toNtp($now);              // transmit timestamp
if ($behaviour === 'short') {
    $response = substr($response, 0, 20);
}
stream_socket_sendto($server, $response, 0, $peer);
