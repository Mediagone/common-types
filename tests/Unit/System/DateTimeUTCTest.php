<?php declare(strict_types=1);

namespace Tests\Mediagone\Common\Types\System;

use DateTime;
use DateTimeInterface;
use DateTimeZone;
use InvalidArgumentException;
use Mediagone\Common\Types\System\Date;
use Mediagone\Common\Types\System\DateTimeUTC;
use PHPUnit\Framework\TestCase;
use function json_encode;


/**
 * @covers \Mediagone\Common\Types\System\DateTimeUTC
 */
final class DateTimeUTCTest extends TestCase
{
    //========================================================================================================
    // Creation Tests
    //========================================================================================================
    
    public function test_can_generate_now() : void
    {
        $now = new DateTime('now', new DateTimeZone('UTC'));
        $utcNow = DateTimeUTC::now();
        
        self::assertSame($now->format('Y-m-d H:i:s'), $utcNow->format('Y-m-d H:i:s'));
    }
    
    public function test_can_generate_today() : void
    {
        $now = new DateTime('today', new DateTimeZone('UTC'));
        $utcToday = DateTimeUTC::today();
        
        self::assertSame($now->format('Y-m-d H:i:s'), $utcToday->format('Y-m-d H:i:s'));
    }
    
    public function test_can_generate_yesterday() : void
    {
        $now = new DateTime('yesterday', new DateTimeZone('UTC'));
        $utcToday = DateTimeUTC::yesterday();
        
        self::assertSame($now->format('Y-m-d H:i:s'), $utcToday->format('Y-m-d H:i:s'));
    }
    
    public function test_can_generate_tomorrow() : void
    {
        $now = new DateTime('tomorrow', new DateTimeZone('UTC'));
        $utcToday = DateTimeUTC::tomorrow();
        
        self::assertSame($now->format('Y-m-d H:i:s'), $utcToday->format('Y-m-d H:i:s'));
    }
    
    
    public function test_can_be_created_from_datetime() : void
    {
        $date = new DateTime('now', new DateTimeZone('UTC'));
        $utcDate = DateTimeUTC::fromDateTime($date);
        
        self::assertSame($date->format('Y-m-d H:i:s.u'), $utcDate->format('Y-m-d H:i:s.u'));
    }
    
    public function test_can_be_created_from_datetime_with_different_timezone() : void
    {
        $date = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $utcDate = DateTimeUTC::fromDateTime($date);
        
        $date->setTimezone(new DateTimeZone('UTC'));
        
        self::assertSame($date->format('Y-m-d H:i:s.u'), $utcDate->format('Y-m-d H:i:s.u'));
    }
    
    
    public function test_can_be_created_from_string() : void
    {
        $atomTime = '2020-01-12T11:22:33+00:00';
        
        $date = DateTime::createFromFormat(DateTimeInterface::ATOM, $atomTime, new DateTimeZone('UTC'));
        $utcDate = DateTimeUTC::fromString($atomTime);
    
        self::assertSame($date->format(DateTimeInterface::ATOM), $utcDate->format(DateTimeInterface::ATOM));
    }
    
    public function test_cannot_be_created_from_invalid_string() : void
    {
        $this->expectException(InvalidArgumentException::class);
        $utcDate = DateTimeUTC::fromString('2020-01-12T11:');
    }
    
    
    
    public function test_can_be_created_from_format() : void
    {
        $time = '2020-01-02 11:22:33.123456';
        $format = 'Y-m-d H:i:s.u';
        
        $date = DateTime::createFromFormat($format, $time, new DateTimeZone('UTC'));
        $utcDate = DateTimeUTC::fromFormat($time, $format);
        
        self::assertSame($date->format($format), $utcDate->format($format));
    }
    
    public function test_can_be_created_from_format_with_specific_timezone() : void
    {
        $time = '2020-01-02 11:22:33.123456';
        $format = 'Y-m-d H:i:s.u';
        
        $date = DateTime::createFromFormat($format, $time, new DateTimeZone('Europe/Paris'));
        $utcDate = DateTimeUTC::fromFormat($time, $format, new DateTimeZone('Europe/Paris'));
        
        $date->setTimezone(new DateTimeZone('UTC'));
        
        self::assertSame($date->format($format), $utcDate->format($format));
    }
    
    public function test_can_be_created_from_format_without_time() : void
    {
        $utcDate = DateTimeUTC::fromFormat('2020-01-02', 'Y-m-d');
        
        self::assertSame('2020-01-02 00:00:00.000000', $utcDate->format('Y-m-d H:i:s.u'));
    }
    
    public function test_cannot_be_created_from_invalid_format() : void
    {
        $this->expectException(InvalidArgumentException::class);
        DateTimeUTC::fromFormat('2020-01-', 'Y-m-d');
    }
    
    
    public function test_can_be_created_from_values() : void
    {
        $utcDate = DateTimeUTC::fromValues(2020, 1, 2);
        self::assertSame('2020-01-02 00:00:00.000000', $utcDate->format('Y-m-d H:i:s.u'));
        
        foreach ([1,1000,2000,9999] as $year) {
            $utcDate = DateTimeUTC::fromValues($year, 1, 2);
            self::assertSame($year, $utcDate->getYear());
        }
    
        foreach (range(1,12) as $month) {
            $utcDate = DateTimeUTC::fromValues(2020, $month, 2);
            self::assertSame($month, $utcDate->getMonth());
        }
    
        foreach (range(1,31) as $day) {
            $utcDate = DateTimeUTC::fromValues(2020, 1, $day);
            self::assertSame($day, $utcDate->getDay());
        }
    }
    
    public function test_can_be_created_from_values_with_hours() : void
    {
        $utcDate = DateTimeUTC::fromValues(2020, 1, 2, 11);
        self::assertSame('2020-01-02 11:00:00.000000', $utcDate->format('Y-m-d H:i:s.u'));
    
        foreach (range(0,23) as $hours) {
            $utcDate = DateTimeUTC::fromValues(2020, 1, 2, $hours);
            self::assertSame($hours, $utcDate->getHours());
        }
    }
    
    public function test_can_be_created_from_values_with_hours_and_minutes() : void
    {
        $utcDate = DateTimeUTC::fromValues(2020, 1, 2, 11, 22);
        self::assertSame('2020-01-02 11:22:00.000000', $utcDate->format('Y-m-d H:i:s.u'));
    
        foreach (range(0,59) as $minutes) {
            $utcDate = DateTimeUTC::fromValues(2020, 1, 2, 0, $minutes);
            self::assertSame($minutes, $utcDate->getMinutes());
        }
    }
    
    public function test_can_be_created_from_values_with_hours_minutes_and_seconds() : void
    {
        $utcDate = DateTimeUTC::fromValues(2020, 1, 2, 11, 22, 33);
        self::assertSame('2020-01-02 11:22:33.000000', $utcDate->format('Y-m-d H:i:s.u'));
    
        foreach (range(0,59) as $seconds) {
            $utcDate = DateTimeUTC::fromValues(2020, 1, 2, 0, 0, $seconds);
            self::assertSame($seconds, $utcDate->getSeconds());
        }
    }
    
    
    public function invalidYearProvider()
    {
        yield [-1];
        yield [0];
        yield [10000];
    }
    
    /**
     * @dataProvider invalidYearProvider
     */
    public function test_cannot_be_created_with_invalid_year($year) : void
    {
        $this->expectException(InvalidArgumentException::class);
        DateTimeUTC::fromValues($year, 1, 2);
    }
    
    
    public function invalidMonthProvider()
    {
        yield [-1];
        yield [0];
        yield [13];
    }
    
    /**
     * @dataProvider invalidMonthProvider
     */
    public function test_cannot_be_created_with_invalid_month($month) : void
    {
        $this->expectException(InvalidArgumentException::class);
        DateTimeUTC::fromValues(2000, $month, 2);
    }
    
    
    public function invalidDayProvider()
    {
        yield [-1];
        yield [0];
        yield [32];
    }
    
    /**
     * @dataProvider invalidDayProvider
     */
    public function test_cannot_be_created_with_invalid_day($day) : void
    {
        $this->expectException(InvalidArgumentException::class);
        DateTimeUTC::fromValues(2000, 1, $day);
    }
    
    
    public function invalidHoursProvider()
    {
        yield [-1];
        yield [24];
    }
    
    /**
     * @dataProvider invalidHoursProvider
     */
    public function test_cannot_be_created_with_invalid_hours($hours) : void
    {
        $this->expectException(InvalidArgumentException::class);
        DateTimeUTC::fromValues(2000, 1, 1, $hours);
    }
    
    
    public function invalidMinutesProvider()
    {
        yield [-1];
        yield [60];
    }
    
    /**
     * @dataProvider invalidMinutesProvider
     */
    public function test_cannot_be_created_with_invalid_minutes($minutes) : void
    {
        $this->expectException(InvalidArgumentException::class);
        DateTimeUTC::fromValues(2000, 1, 1, 1, $minutes);
    }
    
    
    public function invalidSecondsProvider()
    {
        yield [-1];
        yield [60];
    }
    
    /**
     * @dataProvider invalidSecondsProvider
     */
    public function test_cannot_be_created_with_invalid_seconds($seconds) : void
    {
        $this->expectException(InvalidArgumentException::class);
        DateTimeUTC::fromValues(2000, 1, 1, 1, 1, $seconds);
    }
    
    
    public function test_can_be_created_by_modify() : void
    {
        $utcDate = DateTimeUTC::fromFormat('2020-11-12 11:22:33.123456', 'Y-m-d H:i:s.u');
        $utcDateModified = $utcDate->modify('+1 day');
    
        self::assertNotEquals($utcDate, $utcDateModified); // check immutability
        self::assertSame('2020-11-12 11:22:33.123456', $utcDate->format('Y-m-d H:i:s.u'));
        self::assertSame('2020-11-13 11:22:33.123456', $utcDateModified->format('Y-m-d H:i:s.u'));
    }
    
    
    public function test_can_be_set_to_midnight() : void
    {
        $utcDate = DateTimeUTC::fromFormat('2020-11-12 11:22:33.123456', 'Y-m-d H:i:s.u');
        $utcDateModified = $utcDate->midnight();
        
        self::assertNotEquals($utcDate, $utcDateModified); // check immutability
        self::assertSame('2020-11-12 11:22:33.123456', $utcDate->format('Y-m-d H:i:s.u'));
        self::assertSame('2020-11-12 23:59:59.999999', $utcDateModified->format('Y-m-d H:i:s.u'));
    }
    
    
    
    //========================================================================================================
    // Getters Tests
    //========================================================================================================
    
    public function test_can_get_date() : void
    {
        $date = DateTimeUTC::fromValues(2020, 12, 2, 14, 55, 35)->getDate();
        self::assertInstanceOf(Date::class, $date);
        self::assertSame(2020, $date->getYear());
        self::assertSame(12, $date->getMonth());
        self::assertSame(2, $date->getDay());
    }
    
    
    public function test_can_tell_if_past() : void
    {
        self::assertTrue(DateTimeUTC::yesterday()->isPast());
        self::assertFalse(DateTimeUTC::tomorrow()->isPast());
    }
    
    
    public function test_can_tell_if_future() : void
    {
        self::assertFalse(DateTimeUTC::yesterday()->isFuture());
        self::assertTrue(DateTimeUTC::tomorrow()->isFuture());
    }
    
    
    public function test_can_tell_if_today() : void
    {
        self::assertFalse(DateTimeUTC::yesterday()->isToday());
        self::assertTrue(DateTimeUTC::today()->isToday());
        self::assertTrue(DateTimeUTC::now()->isToday());
        self::assertTrue(DateTimeUTC::today()->midnight()->isToday());
        self::assertFalse(DateTimeUTC::tomorrow()->isToday());
    }
    
    
    public function test_can_check_if_value_is_valid() : void
    {
        foreach (['0001','1000','2000','9999'] as $year) {
            $atomTime = "$year-01-01T00:00:00+00:00";
            self::assertTrue(DateTimeUTC::isValueValid($atomTime), "year: $year");
        }
        $base12 = ['01','02','03','04','05','06','07','08','09','10','11','12'];
        foreach ($base12 as $month) {
            $atomTime = "2020-$month-01T00:00:00+00:00";
            self::assertTrue(DateTimeUTC::isValueValid($atomTime), "month: $month");
        }
        $base31 = ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31'];
        foreach ($base31 as $day) {
            $atomTime = "2020-01-{$day}T00:00:00+00:00";
            self::assertTrue(DateTimeUTC::isValueValid($atomTime), "day: $day");
        }
        $base24 = ['00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23'];
        foreach ($base24 as $hours) {
            $atomTime = "2020-01-12T$hours:00:00+00:00";
            self::assertTrue(DateTimeUTC::isValueValid($atomTime), "hours: $hours");
        }
        $base60 = ['00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','32','33','34','35','36','37','38','39','40','41','42','43','44','45','46','47','48','49','50','51','52','53','54','55','56','57','58','59'];
        foreach ($base60 as $minutes) {
            $atomTime = "2020-01-12T00:$minutes:00+00:00";
            self::assertTrue(DateTimeUTC::isValueValid($atomTime), "minutes: $minutes");
        }
        foreach ($base60 as $seconds) {
            $atomTime = "2020-01-12T00:00:$seconds+00:00";
            self::assertTrue(DateTimeUTC::isValueValid($atomTime), "seconds: $seconds");
        }
        $base13 = ['00','01','02','03','04','05','06','07','08','09','10','11','12'];
        foreach ($base13 as $offset) {
            $atomTime = "2020-01-12T00:00:00+$offset:$offset";
            self::assertTrue(DateTimeUTC::isValueValid($atomTime), "offset: $offset");
        }
    }
    
    
    public function test_can_check_if_value_is_a_string() : void
    {
        foreach ([true,1,1.2] as $value) {
            self::assertFalse(DateTimeUTC::isValueValid($value));
        }
    }
    
    
    public function test_can_check_if_value_is_invalid() : void
    {
        foreach ($this->invalidYearProvider() as $year) {
            $atomTime = "{$year[0]}-01-01T00:00:00+00:00";
            self::assertFalse(DateTimeUTC::isValueValid($atomTime), "year: {$year[0]}");
        }
        foreach ($this->invalidMonthProvider() as $month) {
            $atomTime = "2020-{$month[0]}-01T00:00:00+00:00";
            self::assertFalse(DateTimeUTC::isValueValid($atomTime), "month: {$month[0]}");
        }
        foreach ($this->invalidDayProvider() as $day) {
            $atomTime = "2020-01-{$day[0]}T00:00:00+00:00";
            self::assertFalse(DateTimeUTC::isValueValid($atomTime), "day: {$day[0]}");
        }
        foreach ($this->invalidHoursProvider() as $hours) {
            $atomTime = "2020-01-12T{$hours[0]}:00:00+00:00";
            self::assertFalse(DateTimeUTC::isValueValid($atomTime), "hours: {$hours[0]}");
        }
        foreach ($this->invalidMinutesProvider() as $minutes) {
            $atomTime = "2020-01-12T00:{$minutes[0]}:00+00:00";
            self::assertFalse(DateTimeUTC::isValueValid($atomTime), "minutes: {$minutes[0]}");
        }
        foreach ($this->invalidSecondsProvider() as $seconds) {
            $atomTime = "2020-01-12T00:00:{$seconds[0]}+00:00";
            self::assertFalse(DateTimeUTC::isValueValid($atomTime), "seconds: {$seconds[0]}");
        }
        foreach (['0','a'] as $offset) {
            $atomTime = "2020-01-12T00:00:00+$offset:$offset";
            self::assertFalse(DateTimeUTC::isValueValid($atomTime), "offset: $offset");
        }
    }
    
    
    public function test_can_return_year() : void
    {
        $utcDate = DateTimeUTC::fromFormat('2020-01-02 11:22:33.123456', 'Y-m-d H:i:s.u');
        
        self::assertSame(2020, $utcDate->getYear());
    }
    
    public function test_can_return_month() : void
    {
        $utcDate = DateTimeUTC::fromFormat('2020-11-12 11:22:33.123456', 'Y-m-d H:i:s.u');
        
        self::assertSame(11, $utcDate->getMonth());
    }
    
    public function test_can_return_day() : void
    {
        $utcDate = DateTimeUTC::fromFormat('2020-01-12 11:22:33.123456', 'Y-m-d H:i:s.u');
        
        self::assertSame(12, $utcDate->getDay());
    }
    
    public function test_can_return_hours() : void
    {
        $utcDate = DateTimeUTC::fromFormat('2020-01-12 11:22:33.123456', 'Y-m-d H:i:s.u');
        
        self::assertSame(11, $utcDate->getHours());
    }
    
    public function test_can_return_minutes() : void
    {
        $utcDate = DateTimeUTC::fromFormat('2020-01-12 11:22:33.123456', 'Y-m-d H:i:s.u');
        
        self::assertSame(22, $utcDate->getMinutes());
    }
    
    public function test_can_return_seconds() : void
    {
        $utcDate = DateTimeUTC::fromFormat('2020-01-12 11:22:33.123456', 'Y-m-d H:i:s.u');
        
        self::assertSame(33, $utcDate->getSeconds());
    }
    
    
    public function test_can_return_day_of_week() : void
    {
        self::assertSame(Date::MONDAY, DateTimeUTC::fromFormat('2020-01-06 11:22:33.123456', 'Y-m-d H:i:s.u')->getDayOfWeek());
        self::assertSame(Date::TUESDAY, DateTimeUTC::fromFormat('2020-01-07 11:22:33.123456', 'Y-m-d H:i:s.u')->getDayOfWeek());
        self::assertSame(Date::WEDNESDAY, DateTimeUTC::fromFormat('2020-01-08 11:22:33.123456', 'Y-m-d H:i:s.u')->getDayOfWeek());
        self::assertSame(Date::THURSDAY, DateTimeUTC::fromFormat('2020-01-09 11:22:33.123456', 'Y-m-d H:i:s.u')->getDayOfWeek());
        self::assertSame(Date::FRIDAY, DateTimeUTC::fromFormat('2020-01-10 11:22:33.123456', 'Y-m-d H:i:s.u')->getDayOfWeek());
        self::assertSame(Date::SATURDAY, DateTimeUTC::fromFormat('2020-01-11 11:22:33.123456', 'Y-m-d H:i:s.u')->getDayOfWeek());
        self::assertSame(Date::SUNDAY, DateTimeUTC::fromFormat('2020-01-12 11:22:33.123456', 'Y-m-d H:i:s.u')->getDayOfWeek());
    }
    
    
    public function test_can_return_day_of_year() : void
    {
        $utcDate = DateTimeUTC::fromFormat('2020-01-12 11:22:33.123456', 'Y-m-d H:i:s.u');
        
        self::assertSame(12, $utcDate->getDayOfYear());
    }
    
    
    public function test_can_return_week_number() : void
    {
        self::assertSame(1, DateTimeUTC::fromFormat('2020-01-01 11:22:33.123456', 'Y-m-d H:i:s.u')->getWeek());
        self::assertSame(1, DateTimeUTC::fromFormat('2020-01-05 11:22:33.123456', 'Y-m-d H:i:s.u')->getWeek());
        self::assertSame(2, DateTimeUTC::fromFormat('2020-01-06 11:22:33.123456', 'Y-m-d H:i:s.u')->getWeek());
        self::assertSame(52, DateTimeUTC::fromFormat('2020-12-27 11:22:33.123456', 'Y-m-d H:i:s.u')->getWeek());
        self::assertSame(53, DateTimeUTC::fromFormat('2020-12-28 11:22:33.123456', 'Y-m-d H:i:s.u')->getWeek());
        self::assertSame(53, DateTimeUTC::fromFormat('2020-12-31 11:22:33.123456', 'Y-m-d H:i:s.u')->getWeek());
    }
    
    
    
    //========================================================================================================
    // Conversion tests
    //========================================================================================================
    
    public function test_can_be_cast_to_string() : void
    {
        $time = '2020-01-12T11:22:33+00:00';
        $utcDate = DateTimeUTC::fromFormat($time, DateTimeInterface::ATOM);
        
        self::assertSame($time, (string)$utcDate);
    }
    
    public function test_can_be_converted_to_timestamp() : void
    {
        self::assertSame(DateTime::createFromFormat('!Y-m-d', '2020-01-12')->getTimestamp(), DateTimeUTC::fromFormat('2020-01-12', '!Y-m-d')->toTimestamp());
    }
    
    public function test_can_serialize_to_json() : void
    {
        $time = '2020-01-12T11:22:33+00:00';
        $utcDate = DateTimeUTC::fromFormat($time, DateTimeInterface::ATOM);
        
        self::assertSame('"'.$time.'"', json_encode($utcDate));
    }
    
    
    
}
