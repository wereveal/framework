<?php /** @noinspection ALL */

namespace Ritc;

use Ritc\Library\Helper\DatesTimes;

$base_path = dirname(__DIR__, 3);
$o_loader = require $base_path . '/vendor/autoload.php';
$my_namespaces = require $base_path . '/src/config/autoload_namespaces.php';
foreach ($my_namespaces as $psr4_prefix => $psr0_paths) {
    $o_loader->addPsr4($psr4_prefix, $psr0_paths);
}

$format = 'Y-m-d H:i:s';
$other_format = \DateTime::COOKIE;
$time_format = 'H:i:s';
$other_time_format = 'g:i:s a T';
$ts = time();
$end_ts  = time() + (35 * 24 * 60 * 64);
$end_ts_y = time() + (120 * 7 * 24 * 60 * 64);
$tstring = date($format);
$end_tstring = date($format, $end_ts);
$end_tstring_y = date($format, $end_ts_y);
$end_tstring_s = date($format, $ts + 77);
$tz = 'America/Chicago';
$la = 'America/Los_Angeles';
$a_next_formats = [
    'iso',
    'leap',
    'name',
    'short_name',
    'number',
    'short_date',
    'atom',
    'timestamp',
    '',
    $format
];
$a_month_formats = [
    'full',
    'long',
    'short',
    'int',
    'number',
    'default'
];
$a_day_formats = [
    'short',
    'doy',
    'default'
];

$bad  = 'fred';
$bad_other = 'barney';
print 'Timestamp: ' . $ts . "\n";
print 'Timestring: ' . $tstring . "\n";
print 'End str: ' . $end_tstring . "\n";
print 'End str y: ' . $end_tstring_y . "\n";

print "\nDoing isValidDateFormat\n";
print 'empty: '  . DatesTimes::isValidDateFormat() . "\n";
print 'format: ' . DatesTimes::isValidDateFormat($format) . "\n";
print 'other: '  . DatesTimes::isValidDateFormat($other_format) . "\n";
print 'bad: '    . DatesTimes::isValidDateFormat($bad) . "\n";

print "\nDoing convertToYmd\n";
print 'empty: '   . DatesTimes::convertToYmd() . "\n";
print 'ts: '      . DatesTimes::convertToYmd($ts) . "\n";
print 'tstring: ' . DatesTimes::convertToYmd($tstring) . "\n";
print 'bad: '     . DatesTimes::convertToYmd($bad) . "\n";

print "\nDoing convertToYear\n";
print 'empty: '   . DatesTimes::convertToYear() . "\n";
print 'ts: '      . DatesTimes::convertToYear($ts) . "\n";
print 'tstring: ' . DatesTimes::convertToYear($tstring) . "\n";
print 'bad: '     . DatesTimes::convertToYear($bad) . "\n";

print "\nDoing convertToWeek\n";
print 'empty: '   . DatesTimes::convertToWeek() . "\n";
print 'ts: '      . DatesTimes::convertToWeek($ts) . "\n";
print 'tstring: ' . DatesTimes::convertToWeek($tstring) . "\n";
print 'bad: '     . DatesTimes::convertToWeek($bad) . "\n";

print "\nDoing getYesterday\n";
print 'empty: '  . DatesTimes::getYesterday() . "\n";
print 'format: ' . DatesTimes::getYesterday($format) . "\n";
print 'other: '  . DatesTimes::getYesterday($other_format) . "\n";
print 'bad: '    . DatesTimes::getYesterday($bad) . "\n";

print "\nDoing getTomorrow\n";
print 'empty: '  . DatesTimes::getTomorrow() . "\n";
print 'format: ' . DatesTimes::getTomorrow($format) . "\n";
print 'other: '  . DatesTimes::getTomorrow($other_format) . "\n";
print 'bad: '    . DatesTimes::getTomorrow($bad) . "\n";

print "\nDoing convertToTimeWith\n";
print 'empty: '                        . DatesTimes::convertToTimeWith() . "\n";
print 'ts: '                           . DatesTimes::convertToTimeWith('', $ts) . "\n";
print 'ts format: '                    . DatesTimes::convertToTimeWith($time_format, $ts) . "\n";
print 'ts other format: '              . DatesTimes::convertToTimeWith($other_time_format, $ts) . "\n";
print 'ts chicago: '                   . DatesTimes::convertToTimeWith('', $ts, $tz) . "\n";
print 'format ts chicago: '            . DatesTimes::convertToTimeWith($time_format, $ts, $tz) . "\n";
print 'other format ts chicago: '      . DatesTimes::convertToTimeWith($other_time_format, $ts, $tz) . "\n";
print 'ts la: '                        . DatesTimes::convertToTimeWith('', $ts, $la) . "\n";
print 'format ts la: '                 . DatesTimes::convertToTimeWith($time_format, $ts, $la) . "\n";
print 'other format ts la: '           . DatesTimes::convertToTimeWith($other_time_format, $ts, $la) . "\n";
print 'tstring: '                      . DatesTimes::convertToTimeWith('', $tstring) . "\n";
print 'format tstring: '               . DatesTimes::convertToTimeWith($time_format, $tstring) . "\n";
print 'other format tstring: '         . DatesTimes::convertToTimeWith($other_time_format, $tstring) . "\n";
print 'tstring chicago: '              . DatesTimes::convertToTimeWith('', $tstring, $tz) . "\n";
print 'format tstring chicago: '       . DatesTimes::convertToTimeWith($time_format, $tstring, $tz) . "\n";
print 'other format tstring chicago: ' . DatesTimes::convertToTimeWith($other_time_format, $tstring, $tz) . "\n";
print 'tstring la: '                   . DatesTimes::convertToTimeWith('', $tstring, $la) . "\n";
print 'format tstring la: '            . DatesTimes::convertToTimeWith($time_format, $tstring, $la) . "\n";
print 'other format tstring la: '      . DatesTimes::convertToTimeWith($other_time_format, $tstring, $la) . "\n";
print 'bad: '                          . DatesTimes::convertToTimeWith($bad, $bad, $bad) . "\n";


print "\nDoing convertToTimeWithTzOffset\n";
print 'empty: '   . DatesTimes::convertToTimeWithTzOffset() . "\n";
print 'ts: '      . DatesTimes::convertToTimeWithTzOffset($ts) . "\n";
print 'ts chicago: '      . DatesTimes::convertToTimeWithTzOffset($ts, $tz) . "\n";
print 'ts la: '      . DatesTimes::convertToTimeWithTzOffset($ts, $la) . "\n";
print 'tstring: ' . DatesTimes::convertToTimeWithTzOffset($tstring) . "\n";
print 'tstring chicago: ' . DatesTimes::convertToTimeWithTzOffset($tstring, $tz) . "\n";
print 'tstring la: ' . DatesTimes::convertToTimeWithTzOffset($tstring, $la) . "\n";
print 'bad: '     . DatesTimes::convertToTimeWithTzOffset($bad, $bad) . "\n";

print "\nDoing convertToTimeWithTz\n";
print 'empty: '   . DatesTimes::convertToTimeWithTz() . "\n";
print 'ts: '      . DatesTimes::convertToTimeWithTz($ts) . "\n";
print 'ts chicago: '      . DatesTimes::convertToTimeWithTz($ts, $tz) . "\n";
print 'ts la: '      . DatesTimes::convertToTimeWithTz($ts, $la) . "\n";
print 'tstring: ' . DatesTimes::convertToTimeWithTz($tstring) . "\n";
print 'tstring chicago: ' . DatesTimes::convertToTimeWithTz($tstring, $tz) . "\n";
print 'tstring la: ' . DatesTimes::convertToTimeWithTz($tstring, $la) . "\n";
print 'bad: '     . DatesTimes::convertToTimeWithTz($bad, $bad) . "\n";

print "\nDoing convertToTime\n";
print 'empty: '   . DatesTimes::convertToTime() . "\n";
print 'ts: '      . DatesTimes::convertToTime($ts) . "\n";
print 'tstring: ' . DatesTimes::convertToTime($tstring) . "\n";
print 'bad: '     . DatesTimes::convertToTime($bad) . "\n";

print "\nDoing convertToSqlTimestamp\n";
print 'empty: '   . DatesTimes::convertToSqlTimestamp() . "\n";
print 'ts: '      . DatesTimes::convertToSqlTimestamp($ts) . "\n";
print 'tstring: ' . DatesTimes::convertToSqlTimestamp($tstring) . "\n";
print 'bad: '     . DatesTimes::convertToSqlTimestamp($bad) . "\n";

print "\nDoing convertToSqlDate\n";
print 'empty: '   . DatesTimes::convertToSqlDate() . "\n";
print 'ts: '      . DatesTimes::convertToSqlDate($ts) . "\n";
print 'tstring: ' . DatesTimes::convertToSqlDate($tstring) . "\n";
print 'bad: '     . DatesTimes::convertToSqlDate($bad) . "\n";

print "\nDoing convertToShortDate\n";
print 'empty: '   . DatesTimes::convertToShortDate() . "\n";
print 'ts: '      . DatesTimes::convertToShortDate($ts) . "\n";
print 'tstring: ' . DatesTimes::convertToShortDate($tstring) . "\n";
print 'bad: '     . DatesTimes::convertToShortDate($bad) . "\n";

print "\nDoing convertToNextDay\n";
print 'empty: '   . DatesTimes::convertToNextDay() . "\n";
foreach ($a_next_formats as $format) {
    print $format . ' - ts: ' . DatesTimes::convertToNextDay($ts, $format) . "\n";
    print $format . ' - tstring: ' . DatesTimes::convertToNextDay($tstring, $format) . "\n";
}
print 'bad: '     . DatesTimes::convertToNextDay($bad, $bad) . "\n";

print "\nDoing convertToNextWeek\n";
print 'empty: '   . DatesTimes::convertToNextWeek() . "\n";
foreach ($a_next_formats as $format) {
    print $format . ' - ts: ' . DatesTimes::convertToNextWeek($ts, $format) . "\n";
    print $format . ' - tstring: ' . DatesTimes::convertToNextWeek($tstring, $format) . "\n";
}
print 'bad: '     . DatesTimes::convertToNextWeek($bad, $bad) . "\n";

print "\nDoing convertToNextMonth\n";
print 'empty: '   . DatesTimes::convertToNextMonth() . "\n";
foreach ($a_next_formats as $format) {
    print $format . ' - ts: ' . DatesTimes::convertToNextMonth($ts, $format) . "\n";
    print $format . ' - tstring: ' . DatesTimes::convertToNextMonth($tstring, $format) . "\n";
}
print 'bad: '     . DatesTimes::convertToNextMonth($bad, $bad) . "\n";

print "\nDoing convertToNextYear\n";
print 'empty: '   . DatesTimes::convertToNextYear() . "\n";
foreach ($a_next_formats as $format) {
    print $format . ' - ts: ' . DatesTimes::convertToNextYear($ts, $format) . "\n";
    print $format . ' - tstring: ' . DatesTimes::convertToNextYear($tstring, $format) . "\n";
}
print 'bad: '     . DatesTimes::convertToNextYear($bad, $bad) . "\n";

print "\nDoing convertToPreviousYear\n";
print 'empty: '   . DatesTimes::convertToPreviousYear() . "\n";
foreach ($a_next_formats as $format) {
    print $format . ' - ts: ' . DatesTimes::convertToPreviousYear($ts, $format) . "\n";
    print $format . ' - tstring: ' . DatesTimes::convertToPreviousYear($tstring, $format) . "\n";
}
print 'bad: '     . DatesTimes::convertToPreviousYear($bad, $bad) . "\n";

print "\nDoing convertToPreviousMonth\n";
print 'empty: '   . DatesTimes::convertToPreviousMonth() . "\n";
foreach ($a_next_formats as $format) {
    print $format . ' - ts: ' . DatesTimes::convertToPreviousMonth($ts, $format) . "\n";
    print $format . ' - tstring: ' . DatesTimes::convertToPreviousMonth($tstring, $format) . "\n";
}
print 'bad: '     . DatesTimes::convertToPreviousMonth($bad, $bad) . "\n";

print "\nDoing convertToPreviousWeek\n";
print 'empty: '   . DatesTimes::convertToPreviousWeek() . "\n";
foreach ($a_next_formats as $format) {
    print $format . ' - ts: ' . DatesTimes::convertToPreviousWeek($ts, $format) . "\n";
    print $format . ' - tstring: ' . DatesTimes::convertToPreviousWeek($tstring, $format) . "\n";
}
print 'bad: '     . DatesTimes::convertToPreviousWeek($bad, $bad) . "\n";

print "\nDoing convertToMonth\n";
print 'empty: '   . DatesTimes::convertToMonth() . "\n";
foreach ($a_month_formats as $format) {
    print $format . ' - ts: ' . DatesTimes::convertToMonth($ts, $format) . "\n";
    print $format . ' - tstring: ' . DatesTimes::convertToMonth($tstring, $format) . "\n";
}
print 'bad: '     . DatesTimes::convertToMonth($bad, $bad) . "\n";

print "\nDoing getMeridiem\n";
print 'empty: '   . DatesTimes::getMeridiem() . "\n";
print 'ts lc: '      . DatesTimes::getMeridiem($ts) . "\n";
print 'tstring lc: ' . DatesTimes::getMeridiem($tstring) . "\n";
print 'ts uc: '      . DatesTimes::getMeridiem($ts, true) . "\n";
print 'tstring uc: ' . DatesTimes::getMeridiem($tstring, true) . "\n";
print 'bad: '     . DatesTimes::getMeridiem($bad) . "\n";

print "\nDoing convertToLongDate\n";
print 'empty: '   . DatesTimes::convertToLongDate() . "\n";
print 'ts: '      . DatesTimes::convertToLongDate($ts) . "\n";
print 'tstring: ' . DatesTimes::convertToLongDate($tstring) . "\n";
print 'bad: '     . DatesTimes::convertToLongDate($bad) . "\n";

print "\nDoing convertToLongDateTime\n";
print 'empty: '   . DatesTimes::convertToLongDateTime() . "\n";
print 'ts: '      . DatesTimes::convertToLongDateTime($ts) . "\n";
print 'tstring: ' . DatesTimes::convertToLongDateTime($tstring) . "\n";
print 'bad: '     . DatesTimes::convertToLongDateTime($bad) . "\n";

print "\nDoing getDayNumber\n";
print 'empty: ' . DatesTimes::getDayNumber() . "\n";
foreach ($a_day_formats as $day_format) {
    print $day_format . ' tstring: ' . DatesTimes::getDayNumber($tstring, $day_format) . "\n";
    print $day_format . ' ts: ' . DatesTimes::getDayNumber($ts, $day_format) . "\n";
}
print 'bad: ' . DatesTimes::getDayNumber($bad, $bad) . "\n";

print "\nDoing getDayName\n";
print 'empty: '            . DatesTimes::getDayName() . "\n";
print 'timpstamp: '        . DatesTimes::getDayName($end_ts) . "\n";
print 'timestring short: ' . DatesTimes::getDayName($tstring, 'short') . "\n";
print 'bad: '              . DatesTimes::getDayName($bad, $bad) . "\n";

print "\nisUnixTimestamp\n";
print 'empty: '   . (DatesTimes::isUnixTimestamp() ? 'true' : 'false') . "\n";
print 'ts: '      . (DatesTimes::isUnixTimestamp($ts) ? 'true' : 'false') . "\n";
print 'tstring: ' . (DatesTimes::isUnixTimestamp($tstring) ? 'true' : 'false') . "\n";
print 'Bad: '     . (DatesTimes::isUnixTimestamp($bad) ? 'true' : 'false') . "\n";

print "\nDoing convertToUnixTimestamp\n";
print 'empty: '      . DatesTimes::convertToUnixTimestamp() . "\n";
print 'timpstamp: '  . DatesTimes::convertToUnixTimestamp($ts) . "\n";
print 'timestring: ' . DatesTimes::convertToUnixTimestamp($tstring) . "\n";
print 'bad: '        . DatesTimes::convertToUnixTimestamp($bad) . "\n";

print "\nDoing convertDateTimeWith\n";
print 'empty: ' . DatesTimes::convertDateTimeWith() . "\n";
print 'Format Only: ' . DatesTimes::convertDateTimeWith($format) . "\n";
print 'timpstamp: ' . DatesTimes::convertDateTimeWith($other_format, $end_ts) . "\n";
print 'timestring: ' . DatesTimes::convertDateTimeWith($format, $tstring) . "\n";
print 'ts LA: ' . DatesTimes::convertDateTimeWith($other_format, $ts, $la) . "\n";
print 'tstring LA: ' . DatesTimes::convertDateTimeWith($other_format, $tstring, $la) . "\n";
print 'bad: ' . DatesTimes::convertDateTimeWith($bad, $bad, $bad) . "\n";

print "\nDoing isValidTimezone\n";
print 'empty: ' . (DatesTimes::isValidTimezone() ? 'true' : 'false') . "\n";
print 'chicago: ' . (DatesTimes::isValidTimezone($tz) ? 'true' : 'false') . "\n";
print 'LA: ' . (DatesTimes::isValidTimezone($la) ? 'true' : 'false') . "\n";
print 'bad: ' . (DatesTimes::isValidTimezone($bad) ? 'true' : 'false') . "\n";

print "Doing change24hTo12h\n";
print 'empty: ' . DatesTimes::change24hTo12h() . "\n";
print 'Date Only: ' . DatesTimes::change24hTo12h('2018-07-02 16:05:05') . "\n";
print 'f t: ' . DatesTimes::change24hTo12h('2018-07-02 16:05:05', false, true) . "\n";
print 't f: ' . DatesTimes::change24hTo12h('2018-07-02 16:05:05', true, false) . "\n";
print 'bad: ' . DatesTimes::change24hTo12h($bad, $bad, $bad) . "\n";

print "\nchangeTimestampToMidnight\n";
print 'empty: '   . DatesTimes::changeTimestampToMidnight() . "\n";
print 'ts: '      . DatesTimes::changeTimestampToMidnight($ts) . "\n";
print 'stts: '    . DatesTimes::changeTimestampToMidnight($stts) . "\n";
print 'tstring: ' . DatesTimes::changeTimestampToMidnight($tstring) . "\n";
print 'Bad: '     . DatesTimes::changeTimestampToMidnight($bad) . "\n";

print "\ndiffInDays\n";
print 'empty: '   . DatesTimes::diffInDays() . "\n";
print 'good: '      . DatesTimes::diffInDays($tstring, $end_tstring_y) . "\n";
print 'good reverse: ' . DatesTimes::diffInDays($end_tstring_y, $tstring) . "\n";
print 'ts: '    . DatesTimes::diffInDays($ts, $end_ts) . "\n";
print 'missing first: ' . DatesTimes::diffInDays('', $end_tstring_y) . "\n";
print 'missing last: ' . DatesTimes::diffInDays($tstring, '') . "\n";
print 'Bad: '     . DatesTimes::diffInDays($bad, $bad_other) . "\n";

print "\ndiffInMonths\n";
print 'empty: '   . DatesTimes::diffInMonths() . "\n";
print 'good: '      . DatesTimes::diffInMonths($tstring, $end_tstring_y) . "\n";
print 'good reverse: ' . DatesTimes::diffInMonths($end_tstring_y, $tstring) . "\n";
print 'ts: '    . DatesTimes::diffInMonths($ts, $end_ts) . "\n";
print 'missing first: ' . DatesTimes::diffInMonths('', $end_tstring) . "\n";
print 'missing last: ' . DatesTimes::diffInMonths($tstring, '') . "\n";
print 'Bad: '     . DatesTimes::diffInMonths($bad, $bad_other) . "\n";

print "\ndiffInYears\n";
print 'empty: '   . DatesTimes::diffInYears() . "\n";
print 'good: '      . DatesTimes::diffInYears($tstring, $end_tstring_y) . "\n";
print 'good reverse: ' . DatesTimes::diffInYears($end_tstring_y, $tstring) . "\n";
print 'ts: '    . DatesTimes::diffInYears($ts, $end_ts_y) . "\n";
print 'missing first: ' . DatesTimes::diffInYears('', $end_tstring_y) . "\n";
print 'missing last: ' . DatesTimes::diffInYears($tstring, '') . "\n";
print 'Bad: '     . DatesTimes::diffInYears($bad, $bad_other) . "\n";

print "\ngetInterval\n";
print_r(DatesTimes::getInterval()); print "\n";
print_r(DatesTimes::getInterval($tstring, $end_tstring_y)); print "\n";
print_r(DatesTimes::getInterval($end_tstring_y, $tstring)); print "\n";
print_r(DatesTimes::getInterval($ts, $end_ts_y)); print "\n";
print_r(DatesTimes::getInterval('', $end_tstring_y)); print "\n";
print_r(DatesTimes::getInterval($tstring, '')); print "\n";
print_r(DatesTimes::getInterval($bad, $bad_other)); print "\n";

print "\ndiffInHours\n";
print 'empty: '         . DatesTimes::diffInHours() . "\n";
print 'good: '          . DatesTimes::diffInHours($tstring, $end_tstring) . "\n";
print 'good long: '     . DatesTimes::diffInHours($tstring, $end_tstring_y) . "\n";
print 'good reverse: '  . DatesTimes::diffInHours($end_tstring_y, $tstring) . "\n";
print 'ts: '            . DatesTimes::diffInHours($ts, $end_ts_y) . "\n";
print 'missing first: ' . DatesTimes::diffInHours('', $end_tstring_y) . "\n";
print 'missing last: '  . DatesTimes::diffInHours($tstring, '') . "\n";
print 'Bad: '           . DatesTimes::diffInHours($bad, $bad_other) . "\n";

print "\ndiffInSeconds\n";
print 'empty: '         . DatesTimes::diffInSeconds() . "\n";
print 'good: '          . DatesTimes::diffInSeconds($tstring, $end_tstring_s) . "\n";
print 'good long: '     . DatesTimes::diffInSeconds($tstring, $end_tstring_y) . "\n";
print 'good reverse: '  . DatesTimes::diffInSeconds($end_tstring_s, $tstring) . "\n";
print 'ts: '            . DatesTimes::diffInSeconds($ts, $ts + 77) . "\n";
print 'missing first: ' . DatesTimes::diffInSeconds('', $end_tstring_s) . "\n";
print 'missing last: '  . DatesTimes::diffInSeconds($tstring, '') . "\n";
print 'Bad: '           . DatesTimes::diffInSeconds($bad, $bad_other) . "\n";
