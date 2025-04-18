Date
========================================

[![CI](https://github.com/district-5/php-date/actions/workflows/ci.yml/badge.svg?branch=master)](https://github.com/district-5/php-date/actions)
[![Latest Stable Version](http://poser.pugx.org/district5/date/v)](https://packagist.org/packages/district5/date)
[![PHP Version Require](http://poser.pugx.org/district5/date/require/php)](https://packagist.org/packages/district5/date)
[![Codecov](https://codecov.io/gh/district-5/php-date/branch/master/graph/badge.svg)](https://codecov.io/gh/district-5/php-date)

## About

This library comprises two distinct libraries. The old `Date` library, and the new library. It supports most date
functionality.

## Installing

This library requires no other libraries.

```
composer require district5/date
```

### Running Unit Tests:

Unit tests are only against the new library, within the `\District5\Date` namespace.

```
$ composer install --dev
$ ./vendor/bin/phpunit
```


## Usage

<details>
  <summary>Now dates</summary>

```php
<?php
// Get the utc now time
use District5\Date\Date;
use District5\Date\Tz\TzConstants;

$now = Date::now()->utc(); // A datetime object

// Get the time in London now
$now = Date::now()->london(); // A datetime object

// Get the time in ETS now
$now = Date::now()->eastern(); // A datetime object

// Get the time in a specific timezone now
$now = Date::now()->inTimezone(TzConstants::AMERICA_ANCHORAGE); // A datetime object

// Get the epoch as a DateTime
$epoch = Date::epoch();
```

</details>

<details>
  <summary>Start and end of key dates</summary>

```php
<?php
// Get the utc now time
use District5\Date\Date;

$startOfDay = Date::startAndEnd()->startOfDayFromYearMonthDay(2024, 6, 1); // 2024-06-01 00:00:00.000000
$startOfDay = Date::startAndEnd()->startOfDayFromDateTime(Date::createYMDHISM(2024, 6, 1)); // 2024-06-01 00:00:00.000000

$endOfDay = Date::startAndEnd()->endOfDayFromYearMonthDay(2024, 6, 1); // 2024-06-01 23:59:59.999999
$endOfDay = Date::startAndEnd()->endOfDayFromDateTime(Date::createYMDHISM(2024, 6, 1)); // 2024-06-01 23:59:59.999999

$startOfMonth = Date::startAndEnd()->startOfMonthFromYearMonth(2024, 6); // 2024-06-01 00:00:00.000000
$startOfMonth = Date::startAndEnd()->startOfMonthFromDateTime(Date::createYMDHISM(2024, 6, 1)); // 2024-06-01 00:00:00.000000

$endOfMonth = Date::startAndEnd()->endOfMonthFromYearMonth(2024, 6); // 2024-06-30 23:59:59.999999
$endOfMonth = Date::startAndEnd()->endOfMonthFromDateTime(Date::createYMDHISM(2024, 6, 1)); // 2024-06-30 23:59:59.999999

$startOfYear = Date::startAndEnd()->startOfYearFromYear(2024); // 2024-01-01 00:00:00.000000
$startOfYear = Date::startAndEnd()->startOfYearFromDateTime(Date::createYMDHISM(2024, 6, 1)); // 2024-01-01 00:00:00.000000

$endOfYear = Date::startAndEnd()->endOfYearFromYear(2024); // 2024-12-31 23:59:59.999999
$endOfYear = Date::startAndEnd()->endOfYearFromDateTime(Date::createYMDHISM(2024, 6, 1)); // 2024-12-31 23:59:59.999999

$startOfToday = Date::startAndEnd()->startOfToday(); // xxxx-xx-xx 00:00:00.000000
$endOfToday = Date::startAndEnd()->endOfToday(); // xxxx-xx-xx 23:59:59.999999
```

</details>

<details>
  <summary>Modify a date</summary>

#### General:

```php
<?php

use District5\Date\Date;

$date = Date::now()->utc();

// Set the date to the 21st March 2016
$newDate = Date::modify($date)->setDate(21, 3, 2015);

// Set the date to the 21st March 2016 in place -  The original object changes (but is also returned)
Date::modify($date, false)->setDate(21, 3, 2015);

// Set the time to the 18:16:02 and 1 microsecond
$newDate = Date::modify($date)->setTime(18, 56, 2, 1);

// Set the time to the 18:16:02 and 1 microsecond in place -  The original object changes (but is also returned)
Date::modify($date, false)->setTime(18, 56, 2, 1);

// Set the hours of the time to 10
$newDate = Date::modify($date)->setHours(10)

// Set the hours of the time to 10 in place -  The original object changes (but is also returned)
Date::modify($date, false)->setHours(10)

// Set the minutes of the time to 10
$newDate = Date::modify($date)->setMinutes(10)

// Set the minutes of the time to 10 in place -  The original object changes (but is also returned)
Date::modify($date, false)->setMinutes(10)

// Set the seconds of the time to 10
$newDate = Date::modify($date)->setSeconds(10)

// Set the seconds of the time to 10 in place -  The original object changes (but is also returned)
Date::modify($date, false)->setSeconds(10)

// Set the microseconds of the time to 10
$newDate = Date::modify($date)->setMicroseconds(10)

// Set the microseconds of the time to 10 in place -  The original object changes (but is also returned)
Date::modify($date, false)->setMicroseconds(10)

// Apply a string modified to at DateTime
$newDate = Date::modify($date)->withString('-5 seconds');
// Apply a string modified to at DateTime in place -  The original object changes (but is also returned)

Date::modify($date, false)->withString('-5 seconds');
```

#### Minus:

You can chain minus events by using the `minusFluid` method instead of `minus`. This will allow you to chain multiple
minus events together. After completion, you can call `getDateTime` to get the final DateTime object.

Chained minus:

```php
<?php
use District5\Date\Date;

$date = Date::now()->utc();
$new = Date::modify($date)
    ->minusFluid()
    ->millennia(1)
    ->centuries(2)
    ->decades(3)
    ->years(2)
    ->months(5)
    ->days(10)
    ->hours(2)
    ->minutes(5)
    ->seconds(10)
    ->microseconds(2)
    ->milliseconds(2)
    ->getDateTime();
```

```php
<?php
use District5\Date\Date;

$date = Date::now()->utc();

// Take 2 microseconds off a date
$newDate = Date::modify($date)->minus()->microseconds(2);
// Take 2 microseconds off a date in place -  The original object changes (but is also returned)
Date::modify($date, false)->minus()->microseconds(2);

// Take 2 milliseconds off a date
$newDate = Date::modify($date)->minus()->milliseconds(2);
// Take 2 milliseconds off a date in place -  The original object changes (but is also returned)
Date::modify($date, false)->minus()->milliseconds(2);

// Take 2 seconds off a date
$newDate = Date::modify($date)->minus()->seconds(2);
// Take 2 seconds off a date in place -  The original object changes (but is also returned)
Date::modify($date, false)->minus()->seconds(2);

// Take 2 minutes off a date
$newDate = Date::modify($date)->minus()->minutes(2);
// Take 2 minutes off a date in place -  The original object changes (but is also returned)
$newDate = Date::modify($date, false)->minus()->minutes(2);

// Take 2 hours off a date
$newDate = Date::modify($date)->minus()->hours(2);
// Take 2 hours off a date in place -  The original object changes (but is also returned)
$newDate = Date::modify($date, false)->minus()->hours(2);

// Take 2 hours and 5 minutes off a date
$newDate = Date::modify($date)->minus()->hoursAndMinutes(2, 5);
// Take 2 hours and 5 minutes off a date in place -  The original object changes (but is also returned)
$newDate = Date::modify($date, false)->minus()->hoursAndMinutes(2, 5);

// Take 2 hours, 5 minutes and 10 seconds off a date
$newDate = Date::modify($date)->minus()->hoursAndMinutesAndSeconds(2, 5, 10);
// Take 2 hours, 5 minutes and 10 seconds off a date in place -  The original object changes (but is also returned)
$newDate = Date::modify($date, false)->minus()->hoursAndMinutesAndSeconds(2, 5, 10);

// Take 2 days off a date
$newDate = Date::modify($date)->minus()->days(2);
// Take 2 days off a date in place -  The original object changes (but is also returned)
$newDate = Date::modify($date, false)->minus()->days(2);

// Take 2 weeks off a date
$newDate = Date::modify($date)->minus()->weeks(2);
// Take 2 weeks off a date in place -  The original object changes (but is also returned)
$newDate = Date::modify($date, false)->minus()->weeks(2);

// Take 2 months off a date
$newDate = Date::modify($date)->minus()->months(2);
// Take 2 months off a date in place -  The original object changes (but is also returned)
$newDate = Date::modify($date, false)->minus()->months(2);

// Take 2 years off a date
$newDate = Date::modify($date)->minus()->years(2);
// Take 2 years off a date in place -  The original object changes (but is also returned)
$newDate = Date::modify($date, false)->minus()->years(2);

// Take 2 decades off a date
$newDate = Date::modify($date)->minus()->decades(2);
// Take 2 decades off a date in place -  The original object changes (but is also returned)
$newDate = Date::modify($date, false)->minus()->decades(2);

// Take 2 centuries off a date
$newDate = Date::modify($date)->minus()->centuries(2);
// Take 2 centuries off a date in place -  The original object changes (but is also returned)
$newDate = Date::modify($date, false)->minus()->centuries(2);

// Take 2 millennia off a date
$newDate = Date::modify($date)->minus()->millennia(2);
// Take 2 millennia off a date in place -  The original object changes (but is also returned)
$newDate = Date::modify($date, false)->minus()->millennia(2);
```

#### Plus:

You can chain plus events by using the `plusFluid` method instead of `plus`. This will allow you to chain multiple
plus events together. After completion, you can call `getDateTime` to get the final DateTime object.

Chained plus:

```php
<?php
use District5\Date\Date;

$date = Date::now()->utc();
$new = Date::modify($date)
    ->plusFluid()
    ->millennia(1)
    ->centuries(2)
    ->decades(3)
    ->years(2)
    ->months(5)
    ->days(10)
    ->hours(2)
    ->minutes(5)
    ->seconds(10)
    ->microseconds(2)
    ->milliseconds(2)
    ->getDateTime();
```

```php
<?php
use District5\Date\Date;

$date = Date::now()->utc();

// Add 2 microseconds to a date
$newDate = Date::modify($date)->plus()->microseconds(2);

// Add 2 microseconds to a date in place -  The original object changes (but is also returned)
Date::modify($date, false)->plus()->microseconds(2);

// Add 2 milliseconds to a date
$newDate = Date::modify($date)->plus()->milliseconds(2);

// Add 2 milliseconds to a date in place -  The original object changes (but is also returned)
Date::modify($date, false)->plus()->milliseconds(2);

// Add 2 seconds to a date
$newDate = Date::modify($date)->plus()->seconds(2);

// Add 2 seconds to a date in place -  The original object changes (but is also returned)
Date::modify($date, false)->plus()->seconds(2);

// Add 2 minutes to a date
$newDate = Date::modify($date)->plus()->minutes(2);

// Add 2 minutes to a date in place -  The original object changes (but is also returned)
Date::modify($date, false)->plus()->minutes(2);

// Add 2 hours to a date
$newDate = Date::modify($date)->plus()->hours(2);

// Add 2 hours to a date in place -  The original object changes (but is also returned)
Date::modify($date, false)->plus()->hours(2);

// Add 2 hours and 5 minutes to a date
$newDate = Date::modify($date)->plus()->hoursAndMinutes(2, 5);

// Add 2 hours and 5 minutes to a date in place -  The original object changes (but is also returned)
Date::modify($date, false)->plus()->hoursAndMinutes(2, 5);

// Add 2 hours, 5 minutes and 10 seconds to a date
$newDate = Date::modify($date)->plus()->hoursAndMinutesAndSeconds(2, 5, 10);

// Add 2 hours, 5 minutes and 10 seconds to a date in place -  The original object changes (but is also returned)
Date::modify($date, false)->plus()->hoursAndMinutesAndSeconds(2, 5, 10);

// Add 2 days to a date
$newDate = Date::modify($date)->plus()->days(2);

// Add 2 days to a date in place -  The original object changes (but is also returned)
Date::modify($date, false)->plus()->days(2);

// Add 2 weeks to a date
$newDate = Date::modify($date)->plus()->weeks(2);

// Add 2 weeks to a date in place -  The original object changes (but is also returned)
Date::modify($date, false)->plus()->weeks(2);

// Add 2 months to a date
$newDate = Date::modify($date)->plus()->months(2);

// Add 2 months to a date in place -  The original object changes (but is also returned)
Date::modify($date, false)->plus()->months(2);

// Add 2 years to a date
$newDate = Date::modify($date)->plus()->years(2);

// Add 2 years to a date in place -  The original object changes (but is also returned)
Date::modify($date, false)->plus()->years(2);

// Add 2 decades to a date
$newDate = Date::modify($date)->plus()->decades(2);

// Add 2 decades to a date in place -  The original object changes (but is also returned)
Date::modify($date, false)->plus()->decades(2);

// Add 2 centuries to a date
$newDate = Date::modify($date)->plus()->centuries(2);

// Add 2 centuries to a date in place -  The original object changes (but is also returned)
Date::modify($date, false)->plus()->centuries(2);

// Add 2 millennia to a date
$newDate = Date::modify($date)->plus()->millennia(2);

// Add 2 millennia to a date in place -  The original object changes (but is also returned)
Date::modify($date, false)->plus()->millennia(2);
```
</details>

<details>
  <summary>Timezones</summary>

```php
<?php
use District5\Date\Date;
use District5\Date\Tz\TzConstants;

$convertToMadrid = Date::timezone(
    Date::nowDefault()
)->toTimezone(
    TzConstants::EUROPE_MADRID
);

$convertFromMadridToLondon = Date::timezone(
    $convertToMadrid
)->toTimezoneFromTimezone(
    TzConstants::EUROPE_LONDON,
    TzConstants::EUROPE_MADRID
);

// Timezone is also DateTime timezone aware, so if the DateTime has a timezone, you don't need to specify where it's from.
$convertFromMadridToLondonWithoutSpecifyingMadrid = Date::timezone(
    $convertToMadrid
)->toTimezoneFromTimezone(
    TzConstants::EUROPE_LONDON
);
```
</details>

<details>
  <summary>Month operations</summary>

```php
<?php
// Get the utc now time
use District5\Date\Date;

$now = Date::now()->utc(); // A datetime object

// Last day of month from DateTime
$result = Date::month()->lastDateInMonthFromDateTime($now);

// Last day of month from given month and year
$result = Date::month()->lastDateInMonth(2, 2020); // returns 29th Feb 2020
$result = Date::month()->lastDateInMonth(2, 2019); // returns 28th Feb 2019

// Last day in current month
$result = Date::month()->lastDateInCurrentMonth();

// First day of month from DateTime
$result = Date::month()->firstDateInMonthFromDateTime($now);

// First day of month from given month and year
$result = Date::month()->firstDateInMonth(2, 2020);

// First day in current month
$result = Date::month()->firstDateInCurrentMonth();
```
</details>

<details>
  <summary>Mongo dates and times</summary>

```php
<?php
// Get the utc now time
use District5\Date\Date;

$now = Date::now()->utc(); // A datetime object

// Convert the native DateTime to a \MongoDB\BSON\UTCDateTime object
$mongo = Date::mongo()->convertTo($now);

// Convert the \MongoDB\BSON\UTCDateTime object to a native DateTime
$native = Date::mongo()->convertFrom($mongo);
```
</details>

<details>
  <summary>Check if a date is a specific day</summary>

```php
<?php
use District5\Date\Date;
use District5\Date\Validators\DateTimeValidator;

$date = Date::now()->utc();

// Yesterday
$result = Date::validateObject($date)->isYesterday(); // false

// Today
$result = Date::validateObject($date)->isToday(); // true

// Tomorrow
$result = Date::validateObject($date)->isTomorrow(); // false

// Is this a Friday?
$result = Date::validateObject($date)->isDayOfWeekX(DateTimeValidator::DAY_FRIDAY);

// Or the short way:
$result = Date::validateObject($date)->isFriday();

// Is this date May?
$result = Date::validateObject($date)->isMonthNumberX(DateTimeValidator::MONTH_MAY);

// Or the short way:
$result = Date::validateObject($date)->isMay();

// Is this a leap year?
$result = Date::validateObject($date)->isLeapYear();

// Is this AM or PM?
$result = Date::validateObject($date)->isAM();
$result = Date::validateObject($date)->isPM();
```
</details>

<details>
  <summary>Create a date from a given input...</summary>

```php
<?php
// Convert a string date to a DateTime object
use District5\Date\Date;

$dateTime = Date::input('2020-02-05')->fromYMD($separator = '-'); // defaults to '-' as the separator

$dateTime = Date::input('05-02-2020')->fromDMY($separator = '-'); // defaults to '-' as the separator

$dateTime = Date::input('02-05-2020')->fromMDY($separator = '-'); // defaults to '-' as the separator

$dateTime = Date::input('02-05-2020 14:53:59')->fromYMDHIS($dateSeparator = '-', $timeSeparator = ':');

$dateTime = Date::input('1610549639')->fromTimestamp();

$dateTime = Date::input('1610549639')->fromUnixTimestamp();

$dateTime = Date::input('1610549639.001')->fromMicrosecondTimestamp();

$dateTime = Date::input('1610549639.1')->fromMillisecondTimestamp();

$dateTime = Date::input('2021-01-13T14:53:59+0000')->fromISO8601();

// Or use a custom format
$dateTime = Date::input('05/02/2020')->fromFormat($format = 'd/m/Y');

// Convert a string datetime to a DateTime object
$dateTime = Date::input('05/02/2020')->fromYMDHIS($dateSeparator = '-', $timeSeparator = ':'); // defaults to '-' and ':'

// And many more!
```

</details>

<details>
  <summary>Recurring dates (ie, Christmas, Star Wars day etc...)</summary>

```php
<?php
use District5\Date\Date;

$thisYearsDate = Date::recurring()->starWarsDay()->thisYear();

$nextYearsDate = Date::recurring()->starWarsDay()->nextYear(); // Will be the one in the current year +1.

$nextInstance = Date::recurring()->starWarsDay()->getNext(); // Either this year, or next year if today > 4th May.

$lastYear = Date::recurring()->starWarsDay()->getPrevious();
```

</details>

<details>
  <summary>Sorting dates</summary>

```php
<?php
use District5\Date\Date;

$dateInThePast = Date::modify(Date::now()->utc())->minus()->hours(10);
$dateInTheFuture = Date::modify(Date::now()->utc())->plus()->hours(5);

$newestToOldest = Date::sorter()->sortNewestToOldest(
    $dateInTheFuture, 
    $dateInThePast
);

$oldestToNewest = Date::sorter()->sortOldestToNewest(
    $dateInTheFuture, 
    $dateInThePast
);
```
</details>

<details>
  <summary>Diff between dates</summary>

```php
<?php
use District5\Date\Date;

$diff = Date::diff(Date::now()->default());

$diffInSeconds = $diff->seconds(Date::createYMDHISM(2045, 1, 2, 14, 15, 16));

$diffInMinutes = $diff->minutes(Date::createYMDHISM(2045, 1, 2, 14, 15, 16));

$diffInHours = $diff->hours(Date::createYMDHISM(2045, 1, 2, 14, 15, 16));

$diffInDays = $diff->days(Date::createYMDHISM(2045, 1, 2, 14, 15, 16));

$diffInWeeks = $diff->weeks(Date::createYMDHISM(2045, 1, 2, 14, 15, 16));

$diffInMonths = $diff->months(Date::createYMDHISM(2045, 1, 2, 14, 15, 16));

$diffInYears = $diff->years(Date::createYMDHISM(2045, 1, 2, 14, 15, 16));

$diffInDecades = $diff->decades(Date::createYMDHISM(2045, 1, 2, 14, 15, 16));

$diffInCenturies = $diff->centuries(Date::createYMDHISM(2545, 1, 2, 14, 15, 16));

$diffInMillennia = $diff->millennia(Date::createYMDHISM(8545, 1, 2, 14, 15, 16));
```

</details>

<details>
  <summary>Outputting (formatting) a date</summary>

```php
<?php
use District5\Date\Date;
use District5\Date\Tz\TzConstants;

$output = Date::output(
    // 13th January 2021 @ 14:53:59.999000
    Date::createYMDHISM(2021, 1, 13, 14, 53, 59, 999, TzConstants::UTC)
);

$str = $output->toFormat('Y-m-d H:i:s'); // use a custom output format.

$str = $output->getDaySuffix(); // 'th' - The day suffix, for example: rd, th, st etc

$int = $output->getYear(); // 2021 - The integer year of this date

$int = $output->getMonth(); // 1 - The integer month of this date

$int = $output->getWeek(); // 2 - The integer week number, for example 52 (some years have 53)

$int = $output->getDay(); // 13 - The integer day of the month

$str = $output->getHourMinutes(); // '14:53' - The string of HH:MM time

$str = $output->getHourMinutesSeconds(); // '14:53:59' - The string of HH:MM:SS time

$int = $output->getHour(); // 14 - The integer hour of this date

$int = $output->getMinutes(); // 53 - The integer minutes of this date

$int = $output->getSeconds(); // 59 - The integer seconds of this date

$int = $output->getMicroseconds(); // 999000 - The integer microseconds of this date

$int = $output->getMilliseconds(); // 999 - The integer milliseconds of this date

$str = $output->toTimeHM(':'); // '14:53' - The string of HH:MM time using the default separator (can be changed)

$str = $output->toTimeHMS(':'); // '14:53:59' - The string of HH:MM:SS time using the default separator (can be changed)

$str = $output->toYMD(true, '-'); // '2021-01-13' - The string of the date as YYYY-MM-DD using the default separator (can be changed)

$str = $output->toDMY(true, '-'); // '13-01-2021' - The string of the date as DD-MM-YYYY using the default separator (can be changed)

$str = $output->toMDY(true, '-'); // '01-13-2021' - The string of the date as MM-DD-YYYY using the default separator (can be changed)

$str = $output->toTimestamp(true); // '1610549639' - The string (or int if false passed) of the DateTime

$str = $output->toUnixTimestamp(true); // '1610549639' - The string (or int if false passed) of the DateTime

$int = $output->toTimestamp(false); // int(1610549639) - The integer timestamp because false is passed.

$int = $output->toUnixTimestamp(false); // int(1610549639) - The integer timestamp because false is passed.

$str = $output->toMicrosecondTimestamp(true); // '1610549639.000999' - Microsecond timestamp. A string because true is passed

$str = $output->toMillisecondTimestamp(true); // '1610549639.000' - Millisecond timestamp. A string because true is passed

$float = $output->toMicrosecondTimestamp(false); // float(1610549639.001) - Microsecond timestamp. A float because false is passed

$float = $output->toMillisecondTimestamp(false); // float(1610549639) - Millisecond timestamp. A float because false is passed

$str = $output->toISO8601(); // '2021-01-13T14:53:59+0000' - The ISO 8601 format of the DateTime
```
</details>

<details>
  <summary>Old library (`District5\Utility\Date` namespace)</summary>

### PHP Dates

See autocomplete for full list of functions:

```php
\District5\Utility\Date::NowPlusXHours(5);
```

### MongoDB UTCDateTime

See autocomplete for function signatures:

```php
use District5\Utility\DateMongo;


DateMongo::MongoUTCDateTimeToPHPDateTime();

DateMongo::MongoUTCDateTimeToTimestampInt();

DateMongo::MongoUTCDateTimeToTimestampMillisInt();

DateMongo::PHPDateTimeToMongoUTCDateTime();
```

### Stopwatch

The stopwatch class can be instantiated and used to measure time at intervals from stopwatch start:

```php
$stopwatch = new \District5\Utility\Stopwatch();
$stopwatch->start();

...

$secondsPassed = $stopwatch->secondsPassed();
```

You can also set a max time so a boolean check can be made to see if that time has passed. This is useful when working with cron tasks where there is a maximum execution time.

```php
$maxTimeSeconds = 300;

$stopwatch = new \District5\Utility\Stopwatch($maxTimeSeconds);
$stopwatch->start();

while (!$stopwatch->hasMaxTimePassed())
{
    // do some work
}
```

In the above example you should take into account how long an item could take to process an iteration in the while loop to set the max seconds allowed less than the `max time a cron can run` - `time to process 1 loop iteration`. 

### Timezone

Maintains a list of timezones, their offsets from UTC and a human friendly label. See autocomplete for full list of functions.

</details>


## Issues
Log a bug report!

