<?php declare(strict_types=1);

namespace Tests\Mediagone\Common\Types\System;

use DateTime;
use DateTimeZone;
use InvalidArgumentException;
use Mediagone\Common\Types\System\Date;
use PHPUnit\Framework\TestCase;
use function json_encode;
use function range;


/**
 * @covers \Mediagone\Common\Types\System\Date
 */
final class DateTest extends TestCase
{
    //========================================================================================================
    // Creation Tests
    //========================================================================================================
    
    public function test_can_generate_today() : void
    {
        $now = new DateTime('today', new DateTimeZone('UTC'));
        self::assertSame($now->format('Y-m-d'), Date::today()->toString());
    }
    
    public function test_can_generate_yesterday() : void
    {
        $now = new DateTime('yesterday', new DateTimeZone('UTC'));
        self::assertSame($now->format('Y-m-d'), Date::yesterday()->toString());
    }
    
    public function test_can_generate_tomorrow() : void
    {
        $now = new DateTime('tomorrow', new DateTimeZone('UTC'));
        self::assertSame($now->format('Y-m-d'), Date::tomorrow()->toString());
    }
    
    
    public function test_can_be_created_from_datetime() : void
    {
        $format = '2020-08-01';
        $date = new DateTime($format, new DateTimeZone('UTC'));
        self::assertSame($format, Date::fromDateTime($date)->toString());
    }
    
    
    public function test_can_be_created_from_string() : void
    {
        $formatDate = '2020-01-12';
        self::assertSame($formatDate, Date::fromString($formatDate)->toString());
    }
    
    public function test_cannot_be_created_from_invalid_string() : void
    {
        $this->expectException(InvalidArgumentException::class);
        Date::fromString('020-01-12:');
    }
    
    
    
    public function test_can_be_created_from_format() : void
    {
        $time = '2020-01-02';
        $format = 'Y-m-d';
        
        $date = DateTime::createFromFormat($format, $time, new DateTimeZone('UTC'));
        $utcDate = Date::fromFormat($time, $format);
        
        self::assertSame($date->format($format), $utcDate->format($format));
    }
    
    public function test_can_be_created_from_format_without_time() : void
    {
        $utcDate = Date::fromFormat('2020-01-02', 'Y-m-d');
        
        self::assertSame('2020-01-02 00:00:00.000000', $utcDate->format('Y-m-d H:i:s.u'));
    }
    
    public function test_cannot_be_created_from_invalid_format() : void
    {
        $this->expectException(InvalidArgumentException::class);
        Date::fromFormat('2020-01-', 'Y-m-d');
    }
    
    
    
    public function test_can_be_created_from_values() : void
    {
        $utcDate = Date::fromValues(2020, 1, 2);
        self::assertSame('2020-01-02', $utcDate->toString());
        
        foreach ([1,1000,2000,9999] as $year) {
            $utcDate = Date::fromValues($year, 1, 2);
            self::assertSame($year, $utcDate->getYear());
        }
    
        foreach (range(1,12) as $month) {
            $utcDate = Date::fromValues(2020, $month, 2);
            self::assertSame($month, $utcDate->getMonth());
        }
    
        foreach (range(1,31) as $day) {
            $utcDate = Date::fromValues(2020, 1, $day);
            self::assertSame($day, $utcDate->getDay());
        }
    }
    
    
    /**
     * @dataProvider invalidYearProvider
     */
    public function test_cannot_be_created_with_invalid_year($year) : void
    {
        $this->expectException(InvalidArgumentException::class);
        Date::fromValues($year, 1, 2);
    }
    
    public function invalidYearProvider()
    {
        yield [-1];
        yield [0];
        yield [10000];
    }
    
    
    /**
     * @dataProvider invalidMonthProvider
     */
    public function test_cannot_be_created_with_invalid_month($month) : void
    {
        $this->expectException(InvalidArgumentException::class);
        Date::fromValues(2000, $month, 2);
    }
    
    public function invalidMonthProvider()
    {
        yield [-1];
        yield [0];
        yield [13];
    }
    
    
    /**
     * @dataProvider invalidDayProvider
     */
    public function test_cannot_be_created_with_invalid_day($day) : void
    {
        $this->expectException(InvalidArgumentException::class);
        Date::fromValues(2000, 1, $day);
    }
    
    public function invalidDayProvider()
    {
        yield [-1];
        yield [0];
        yield [32];
    }
    
    
    
    //========================================================================================================
    // Getters Tests
    //========================================================================================================
    
    public function test_can_tell_if_past() : void
    {
        self::assertTrue(Date::yesterday()->isPast());
        self::assertFalse(Date::today()->isPast());
        self::assertFalse(Date::tomorrow()->isPast());
    }
    
    
    public function test_can_tell_if_future() : void
    {
        self::assertFalse(Date::yesterday()->isFuture());
        self::assertFalse(Date::today()->isFuture());
        self::assertTrue(Date::tomorrow()->isFuture());
    }
    
    
    public function test_can_tell_if_today() : void
    {
        self::assertFalse(Date::yesterday()->isToday());
        self::assertTrue(Date::today()->isToday());
        self::assertFalse(Date::tomorrow()->isToday());
    }
    
    
    public function test_can_check_if_value_is_valid() : void
    {
        foreach (['0001','1000','2000','9999'] as $year) {
            $date = "$year-01-01";
            self::assertTrue(Date::isValueValid($date), "year: $year");
        }
        $base12 = ['01','02','03','04','05','06','07','08','09','10','11','12'];
        foreach ($base12 as $month) {
            $date = "2020-$month-01";
            self::assertTrue(Date::isValueValid($date), "month: $month");
        }
        $base31 = ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31'];
        foreach ($base31 as $day) {
            $date = "2020-01-{$day}";
            self::assertTrue(Date::isValueValid($date), "day: $day");
        }
    }
    
    
    public function test_can_check_if_value_is_a_string() : void
    {
        foreach ([true,1,1.2] as $value) {
            self::assertFalse(Date::isValueValid($value));
        }
    }
    
    
    public function test_can_check_if_value_is_invalid() : void
    {
        foreach ($this->invalidYearProvider() as $year) {
            $atomTime = "{$year[0]}-01-01";
            self::assertFalse(Date::isValueValid($atomTime), "year: {$year[0]}");
        }
        foreach ($this->invalidMonthProvider() as $month) {
            $atomTime = "2020-{$month[0]}-01";
            self::assertFalse(Date::isValueValid($atomTime), "month: {$month[0]}");
        }
        foreach ($this->invalidDayProvider() as $day) {
            $atomTime = "2020-01-{$day[0]}";
            self::assertFalse(Date::isValueValid($atomTime), "day: {$day[0]}");
        }
    }
    
    
    public function test_can_return_year() : void
    {
        self::assertSame(2020, Date::fromValues(2020, 11, 29)->getYear());
    }
    
    public function test_can_return_month() : void
    {
        self::assertSame(11, Date::fromValues(2020, 11, 29)->getMonth());
    }
    
    public function test_can_return_day() : void
    {
        self::assertSame(29, Date::fromValues(2020, 11, 29)->getDay());
    }
    
    
    public function test_can_return_day_of_week() : void
    {
        self::assertSame(Date::MONDAY, Date::fromValues(2020, 1, 6)->getDayOfWeek());
        self::assertSame(Date::TUESDAY, Date::fromValues(2020, 1, 7)->getDayOfWeek());
        self::assertSame(Date::WEDNESDAY, Date::fromValues(2020, 1, 8)->getDayOfWeek());
        self::assertSame(Date::THURSDAY, Date::fromValues(2020, 1, 9)->getDayOfWeek());
        self::assertSame(Date::FRIDAY, Date::fromValues(2020, 1, 10)->getDayOfWeek());
        self::assertSame(Date::SATURDAY, Date::fromValues(2020, 1, 11)->getDayOfWeek());
        self::assertSame(Date::SUNDAY, Date::fromValues(2020, 1, 12)->getDayOfWeek());
    }
    
    
    public function test_can_return_day_of_year() : void
    {
        self::assertSame(12, Date::fromValues(2020, 1, 12)->getDayOfYear());
    }
    
    
    public function test_can_return_week_number() : void
    {
        self::assertSame(1, Date::fromValues(2020, 1, 1)->getWeek());
        self::assertSame(1, Date::fromValues(2020, 1, 5)->getWeek());
        self::assertSame(2, Date::fromValues(2020, 1, 6)->getWeek());
        self::assertSame(52, Date::fromValues(2020, 12, 27)->getWeek());
        self::assertSame(53, Date::fromValues(2020, 12, 28)->getWeek());
        self::assertSame(53, Date::fromValues(2020, 12, 31)->getWeek());
    }
    
    
    
    //========================================================================================================
    // Conversion tests
    //========================================================================================================
    
    public function test_can_be_converted_to_string() : void
    {
        self::assertSame('2020-01-12', Date::fromString('2020-01-12')->toString());
    }
    
    public function test_can_be_converted_to_timestamp() : void
    {
        self::assertSame(DateTime::createFromFormat('!Y-m-d', '2020-01-12')->getTimestamp(), Date::fromString('2020-01-12')->toTimestamp());
    }
    
    public function test_can_serialize_to_json() : void
    {
        self::assertSame('"2020-01-12"', json_encode(Date::fromString('2020-01-12')));
    }
    
    
    
}
