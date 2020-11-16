Date
========================================

## About

This library is designed to be a collection of date utilities. It supports functionality for:

* PHP dates
* MongoDB UTCDateTime
* Stopwatches
* Timezones


## Installing

This library requires no other libraries.

* Require in your composer
    * `"district5/date": "*"`


## Usage

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

## Issues
Log a bug report!

## Questions
Package Authors:

* Mark Morgan - mark.morgan@district5.co.uk

