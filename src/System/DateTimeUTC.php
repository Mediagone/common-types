<?php declare(strict_types=1);

namespace Mediagone\Common\Types\System;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use InvalidArgumentException;
use Mediagone\Common\Types\ValueObject;
use function is_string;
use function preg_match;
use function str_pad;
use function time;


/**
 * Represents an UTC DateTime in ATOM format.
 */
final class DateTimeUTC implements ValueObject
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private static ?DateTimeZone $utc = null;
    
    private static function getUTC() : DateTimeZone
    {
        if (self::$utc === null) {
            self::$utc = new DateTimeZone('UTC');
        }
        
        return self::$utc;
    }
    
    
    private DateTimeImmutable $datetime;
    
    
    
    
    //========================================================================================================
    // Constructors
    //========================================================================================================
    
    private function __construct(DateTimeImmutable $datetime)
    {
        if ($datetime->getTimezone()->getName() !== 'UTC') {
            $datetime = $datetime->setTimezone(self::getUTC());
        }
        
        $this->datetime = $datetime;
    }
    
    
    public static function now(?DateTimeZone $timezone = null) : self
    {
        $datetime = new DateTime('now', $timezone ?? self::getUTC());
        $year = (int)$datetime->format('Y');
        $month = (int)$datetime->format('m');
        $day = (int)$datetime->format('d');
        $hours = (int)$datetime->format('H');
        $minutes = (int)$datetime->format('i');
        $seconds = (int)$datetime->format('s');
        
        return self::fromValues($year, $month, $day, $hours, $minutes, $seconds, self::getUTC());
        
    }
    
    
    public static function today(?DateTimeZone $timezone = null) : self
    {
        $datetime = new DateTime('today', $timezone ?? self::getUTC());
        $year = (int)$datetime->format('Y');
        $month = (int)$datetime->format('m');
        $day = (int)$datetime->format('d');
        
        return self::fromValues($year, $month, $day, 0, 0, 0, self::getUTC());
    }
    
    
    public static function tomorrow(?DateTimeZone $timezone = null) : self
    {
        $datetime = new DateTime('tomorrow', $timezone ?? self::getUTC());
        $year = (int)$datetime->format('Y');
        $month = (int)$datetime->format('m');
        $day = (int)$datetime->format('d');
        
        return self::fromValues($year, $month, $day, 0, 0, 0, self::getUTC());
    }
    
    
    public static function yesterday(?DateTimeZone $timezone = null) : self
    {
        $datetime = new DateTime('yesterday', $timezone ?? self::getUTC());
        $year = (int)$datetime->format('Y');
        $month = (int)$datetime->format('m');
        $day = (int)$datetime->format('d');
    
        return self::fromValues($year, $month, $day, 0, 0, 0, self::getUTC());
    }
    
    
    /* Days of week */
    
    public static function mondayThisWeek() : self
    {
        return new self(new DateTimeImmutable('Monday this week', self::getUTC()));
    }
    
    public static function tuesdayThisWeek() : self
    {
        return new self(new DateTimeImmutable('Tuesday this week', self::getUTC()));
    }
    
    public static function wednesdayThisWeek() : self
    {
        
        return new self(new DateTimeImmutable('Wednesday this week', self::getUTC()));
    }
    
    public static function thursdayThisWeek() : self
    {
        return new self(new DateTimeImmutable('Thursday this week', self::getUTC()));
    }
    
    public static function fridayThisWeek() : self
    {
        return new self(new DateTimeImmutable('Friday this week', self::getUTC()));
    }
    
    public static function saturdayThisWeek() : self
    {
        return new self(new DateTimeImmutable('Saturday this week', self::getUTC()));
    }
    
    public static function sundayThisWeek() : self
    {
        return new self(new DateTimeImmutable('Sunday this week', self::getUTC()));
    }
    
    public static function lastMonday() : self
    {
        return self::tomorrow()->modify('previous Monday');
    }
    
    public static function lastTuesday() : self
    {
        return self::tomorrow()->modify('previous Tuesday');
    }
    
    public static function lastWednesday() : self
    {
        
        return self::tomorrow()->modify('previous Wednesday');
    }
    
    public static function lastThursday() : self
    {
        return self::tomorrow()->modify('previous Thursday');
    }
    
    public static function lastFriday() : self
    {
        return self::tomorrow()->modify('previous Friday');
    }
    
    public static function lastSaturday() : self
    {
        return self::tomorrow()->modify('previous Saturday');
    }
    
    public static function lastSunday() : self
    {
        return self::tomorrow()->modify('previous Sunday');
    }
    
    
    
    
    
    
    public static function fromDateTime(DateTime $datetime) : self
    {
        return new self(DateTimeImmutable::createFromMutable($datetime));
    }
    
    
    public static function fromDateTimeImmutable(DateTimeImmutable $datetime) : self
    {
        return new self($datetime);
    }
    
    
    public static function fromString(string $value, ?DateTimeZone $timezone = null) : self
    {
        if ($timezone === null) {
            $timezone = self::getUTC();
        }
    
        $datetime = DateTimeImmutable::createFromFormat(DateTimeInterface::ATOM, $value, $timezone);
        if (! $datetime) {
            throw new InvalidArgumentException("Invalid DateTimeUTC value ($value), it must follow ATOM format.");
        }
        
        return new self($datetime);
    }
    
    
    public static function fromFormat(string $value, string $format, ?DateTimeZone $timezone = null) : self
    {
        // Ensure all datetime fields are reset if not specified in the format
        if ($format[0] !== '!') {
            $format = "!$format";
        }
        
        $datetime = DateTimeImmutable::createFromFormat($format, $value, $timezone ?? self::getUTC());
        if (! $datetime) {
            throw new InvalidArgumentException("Invalid DateTimeUTC value ($value) or format ($format)");
        }
        
        return new self($datetime);
    }
    
    
    public static function fromValues(int $year, int $month, int $day, int $hours = 0, int $minutes = 0, int $seconds = 0, ?DateTimeZone $timezone = null) : self
    {
        if ($year < 1 || $year > 9999) {
            throw new InvalidArgumentException('Invalid date "year" value ('.$year.'), it must be between [1-9999]');
        }
        $year = str_pad((string)$year, 4, '0', STR_PAD_LEFT);
        
        if ($month < 1 || $month > 12) {
            throw new InvalidArgumentException('Invalid date "month" value ('.$month.'), it must be between [1-12]');
        }
        $month = str_pad((string)$month, 2, '0', STR_PAD_LEFT);
    
        if ($day < 1 || $day > 31) {
            throw new InvalidArgumentException('Invalid date "day" value ('.$day.'), it must be between [1-31]');
        }
        $day = str_pad((string)$day, 2, '0', STR_PAD_LEFT);
        
        if ($hours < 0 || $hours > 23) {
            throw new InvalidArgumentException('Invalid date "hours" value ('.$hours.'), it must be between [0-23]');
        }
        $hours = str_pad((string)$hours, 2, '0', STR_PAD_LEFT);
    
        if ($minutes < 0 || $minutes > 59) {
            throw new InvalidArgumentException('Invalid date "minutes" value ('.$minutes.'), it must be between [0-59]');
        }
        $minutes = str_pad((string)$minutes, 2, '0', STR_PAD_LEFT);
    
        if ($seconds < 0 || $seconds > 59) {
            throw new InvalidArgumentException('Invalid date "seconds" value ('.$seconds.'), it must be between [0-59]');
        }
        $seconds = str_pad((string)$seconds, 2, '0', STR_PAD_LEFT);
        
        $datetime = DateTimeImmutable::createFromFormat('!Y-m-d H:i:s', "$year-$month-$day $hours:$minutes:$seconds", $timezone ?? self::getUTC());
        
        return new self($datetime);
    }
    
    
    
    //========================================================================================================
    // Static methods
    //========================================================================================================
    
    /**
     * @param string $value
     */
    public static function isValueValid($value) : bool
    {
        if (! is_string($value)) {
            return false;
        }
        
        $regex = '#^'
            . '[0-9]{4}' // year
            . '-(01|02|03|04|05|06|07|08|09|10|11|12)' // month
            . '-(01|02|03|04|05|06|07|08|09|10|11|12|13|14|15|16|17|18|19|20|21|22|23|24|25|26|27|28|29|30|31)' // day
            . 'T(00|01|02|03|04|05|06|07|08|09|10|11|12|13|14|15|16|17|18|19|20|21|22|23)' // hours
            . ':(00|01|02|03|04|05|06|07|08|09|10|11|12|13|14|15|16|17|18|19|20|21|22|23|24|25|26|27|28|29|30|31|32|33|34|35|36|37|38|39|40|41|42|43|44|45|46|47|48|49|50|51|52|53|54|55|56|57|58|59)' // minutes
            . ':(00|01|02|03|04|05|06|07|08|09|10|11|12|13|14|15|16|17|18|19|20|21|22|23|24|25|26|27|28|29|30|31|32|33|34|35|36|37|38|39|40|41|42|43|44|45|46|47|48|49|50|51|52|53|54|55|56|57|58|59)' // seconds
            . '(-|\+)[0-9]{2}:[0-9]{2}' // timezone
            . '$#';
        
        return preg_match($regex, $value) === 1;
    }
    
    
    
    //========================================================================================================
    // Methods
    //========================================================================================================
    
    public function jsonSerialize()
    {
        return $this->datetime->format(DateTimeInterface::ATOM);
    }
    
    
    public function __toString() : string
    {
        return $this->datetime->format(DateTimeInterface::ATOM);
    }
    
    
    public function toTimestamp() : int
    {
        return $this->datetime->getTimestamp();
    }
    
    
    public function format(string $format) : string
    {
        return $this->datetime->format($format);
    }
    
    
    public function modify(string $modify) : self
    {
        return new self($this->datetime->modify($modify));
    }
    
    
    public function midnight() : self
    {
        return new self($this->datetime->setTime(23,59,59,999999));
    }
    
    
    public function isPast() : bool
    {
        return $this->datetime->getTimestamp() < time();
    }
    
    
    public function isFuture() : bool
    {
        return $this->datetime->getTimestamp() > time();
    }
    
    
    public function isToday() : bool
    {
        $startOfDay = self::today();
        $endOfDay = $startOfDay->midnight();
        $timestamp = $this->toTimestamp();
        
        return $timestamp >= $startOfDay->toTimestamp() && $timestamp <= $endOfDay->toTimestamp();
    }
    
    
    public function getYear() : int
    {
        return (int)$this->datetime->format('Y');
    }
    
    
    public function getMonth() : int
    {
        return (int)$this->datetime->format('m');
    }
    
    
    public function getDay() : int
    {
        return (int)$this->datetime->format('d');
    }
    
    
    public function getHours() : int
    {
        return (int)$this->datetime->format('H');
    }
    
    
    public function getMinutes() : int
    {
        return (int)$this->datetime->format('i');
    }
    
    
    public function getSeconds() : int
    {
        return (int)$this->datetime->format('s');
    }
    
    
    public function getDayOfWeek() : int
    {
        return (int)$this->datetime->format('N');
    }
    
    
    public function getDayOfYear() : int
    {
        return ((int)$this->datetime->format('z') + 1);
    }
    
    
    public function getWeek() : int
    {
        return (int)$this->datetime->format('W');
    }
    
    
    public function getDate() : Date
    {
        return Date::fromValues($this->getYear(), $this->getMonth(), $this->getDay());
    }
    
    
    
}
