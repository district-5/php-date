# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

This release drops PHP 8.1 support and fixes a number of bugs. Several fixes change
behaviour that existing code may rely on — see **Changed** below before upgrading.

### Added

- PHP 8.5 and PHP 8.6 support. CI now runs the test suite against PHP 8.4, 8.5 and 8.6.
- `Timezone::toTimezone()` and `Timezone::toTimezoneFromTimezone()` accept any
  `DateTimeZone` object, including offset zones such as `+05:30`.
- `InputFormatter::fromISO8601()` accepts a `Z` suffix, offsets without a colon
  (`-0800`) and fractional seconds.
- `Date::createYMDHISM()` accepts any timezone PHP recognises (e.g. `Etc/GMT+5`),
  including when it is the PHP default timezone.
- `NtpServer::getMicrotime()` returns the NTP time with sub-second precision, corrected for network delay.
- More default NTP servers: Cloudflare, Apple, AWS, Microsoft, the NTP Pool and NIST, after the existing Google servers.
- `NtpServer` servers may be given as `host:port`, an IPv6 address, or `[ipv6]:port`.
- `TzConstants` includes 16 current IANA zone names that were missing, including the new names of
  renamed zones (`Europe/Kyiv`, `America/Nuuk`, `Asia/Yangon`, `Pacific/Kanton`). The old names are kept,
  but note that systems without legacy tzdata (e.g. Ubuntu 24.04+ without `tzdata-legacy`) reject them.
- Greatly expanded test suite (292 → 1,359 tests; `src/Date` line coverage 84% → 97%).

### Changed

- **PHP requirement** is now `~8.2.35 || ~8.3.36 || ~8.4.27 || >=8.5.12` (previously `>=8.1`).
- **Out-of-range time values are rejected** instead of rolling over. `Modify::setHours()`,
  `setMinutes()`, `setSeconds()`, `setMicroseconds()` and `setTime()` now return `false`
  for values such as hour 24/25, minute 60 or second 60 in both clone and in-place modes.
  `Date::createYMDHISM()` and `InputFormatter::fromYMDHIS()` likewise return `false`
  (e.g. `2023-02-29 10:00:00` or `25:00:00`).
- **Invalid months/days throw `InvalidArgumentException`.** `Month::firstDateInMonth()`,
  `lastDateInMonth()` and `numberDaysInMonth()` throw for a month outside 1–12
  (previously a `TypeError`). `Calculate::numberDaysLeftInMonthByGivenValues()` throws for
  an invalid day or month (previously returned `0`, indistinguishable from the last day).
- **Sub-second precision is respected.** `Sorter`, `newest()`, `oldest()` and
  `DateTimeValidator::isBetween()` now take microseconds into account.
- **Separators are literal.** Separator arguments to `InputFormatter`, `OutputFormatter`
  and `StringFormatValidator::isYmd()` are escaped, so letters are no longer treated as
  format codes (`toTimeHM('h')` now returns `07h08`, previously `070708`).
- `PlusFluid` / `MinusFluid` honour `cloneDateTime = false` and modify the passed DateTime.
- `Diff::months()`, `years()`, `decades()`, `centuries()` and `millennia()` return the same
  result regardless of argument order.
- `Timezone::toTimezone()` parameter type widened from `string` to `string|DateTimeZone`.
  Subclasses overriding this method must update their signature.
- `NtpServer` timeouts: the connection and read timeouts are now both
  `$secondsTimeout + $millisecondTimeout / 1000`. A total of zero falls back to 1 second.
- `NtpServer` sends NTP v4 requests (previously v1) with a random transmit timestamp, and
  `getTimestamp()` / `getObject()` are now corrected for network round-trip delay.

### Fixed

- `StartAndEnd::startOfDayFromYearMonthDay()` / `endOfDayFromYearMonthDay()` returned the
  start/end of the month instead of the day.
- `Month::lastDateInCurrentMonth()` returned the first day of the month.
- `RecurringDateManipulator::getPrevious()` returned next year's date for a future date.
- `RecurringDateManipulator::isToday()` ignored the stored date and returned `true` for
  any date earlier in the year.
- `Modify` clone-mode time setters dropped the input's timezone, returning a different instant.
- `Modify::setDate()` and `Plus` / `Minus` `hoursAndMinutes*()` in in-place mode partially
  modified the input before returning `false`.
- `Calculate::numberDaysLeftInMonthByGivenValues()` used the month number as the default day.
- `Date::epoch()` depended on the default timezone (e.g. `-3600` under Europe/London);
  it is now always timestamp `0` in UTC.
- `MongoConverter::toObjectId()` interpreted the time in the default timezone rather than
  the DateTime's own; it now returns `false` for timestamps outside the ObjectId range.
- `MongoConverter::convertTo()` was occasionally 1ms low due to float rounding.
- `OutputFormatter::toMillisecondTimestamp()` / `toMicrosecondTimestamp()` were a full second
  out for pre-1970 instants with a fractional second (`-1.5` instead of `-0.5`);
  `InputFormatter::fromMillisecondTimestamp()` / `fromMicrosecondTimestamp()` now parse
  negative values to match.
- `InputFormatter` lost precision on float input (e.g. `1700000000.123456`).
- `StringFormatValidator::isYmd()` always returned `false` for integer input.
- `Timezone::toTimezoneFromTimezone()` threw a `TypeError` for an invalid source timezone
  or an offset-zone source DateTime; it now returns `false` or converts correctly.
- `NtpServer` reliability: sockets are always closed; timeouts, empty and truncated replies are
  detected; replies are rejected (and the next server tried) if they are not a server reply, come from an
  unsynchronised server, are a kiss-o'-death (rate limiting / deny), or do not echo the request's token.
  Timestamps after the NTP era rollover in February 2036 are handled, and IPv6 addresses work.
- `Stopwatch` no longer triggers the PHP 8.4+ implicitly-nullable parameter deprecation.
- Docblocks for `getMilliseconds()` (truncates) and `Diff` / `Calculate` `hours()` /
  `minutes()` (rounds) now describe the actual behaviour.

[Unreleased]: https://github.com/district-5/php-date/compare/3.0.4...HEAD
