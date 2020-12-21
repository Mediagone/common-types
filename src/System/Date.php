<?php declare(strict_types=1);

namespace Mediagone\Common\Types\System;

use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use InvalidArgumentException;
use Mediagone\Common\Types\ValueObject;
use function str_pad;


/**
 * Represents a Date in YYYY-MM-DD format.
 */
final class Date implements ValueObject
{
    //========================================================================================================
    // Constants
    //========================================================================================================
    
    public const MONDAY = 1;
    
    public const TUESDAY = 2;
    
    public const WEDNESDAY = 3;
    
    public const THURSDAY = 4;
    
    public const FRIDAY = 5;
    
    public const SATURDAY = 6;
    
    public const SUNDAY = 7;
    
    
    
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
    
    
    public static function today(?DateTimeZone $timezone = null) : self
    {
        $datetime = new DateTime('today', $timezone ?? self::getUTC());
        $year = (int)$datetime->format('Y');
        $month = (int)$datetime->format('m');
        $day = (int)$datetime->format('d');
    
        return self::fromValues($year, $month, $day);
    }
    
    
    public static function tomorrow(?DateTimeZone $timezone = null) : self
    {
        $datetime = new DateTime('tomorrow', $timezone ?? self::getUTC());
        $year = (int)$datetime->format('Y');
        $month = (int)$datetime->format('m');
        $day = (int)$datetime->format('d');
    
        return self::fromValues($year, $month, $day);
    }
    
    
    public static function yesterday(?DateTimeZone $timezone = null) : self
    {
        $datetime = new DateTime('yesterday', $timezone ?? self::getUTC());
        $year = (int)$datetime->format('Y');
        $month = (int)$datetime->format('m');
        $day = (int)$datetime->format('d');
    
        return self::fromValues($year, $month, $day);
    }
    
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
        return new self(DateTimeImmutable::createFromMutable($datetime)->setTime(0,0,0,0));
    }
    
    
    public static function fromString(string $value) : self
    {
        $datetime = DateTimeImmutable::createFromFormat('!Y-m-d', $value);
        if (! $datetime) {
            throw new InvalidArgumentException("Invalid Date value ($value), it must follow 'Y-m-d' format.");
        }
        
        return new self($datetime);
    }
    
    
    public static function fromFormat(string $value, string $format) : self
    {
        // Ensure all datetime fields are reset if not specified in the format
        if ($format[0] !== '!') {
            $format = "!$format";
        }
        
        $date = DateTimeImmutable::createFromFormat($format, $value);
        if (! $date) {
            throw new InvalidArgumentException("Invalid Date value ($value) or format ($format)");
        }
        
        return new self($date);
    }
    
    
    public static function fromValues(int $year, int $month, int $day) : self
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
        
        return new self(DateTimeImmutable::createFromFormat('!Y-m-d', "$year-$month-$day"));
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
            . '$#';
        
        return preg_match($regex, $value) === 1;
    }
    
    
    
    //========================================================================================================
    // Methods
    //========================================================================================================
    
    public function jsonSerialize()
    {
        return $this->datetime->format('Y-m-d');
    }
    
    
    public function __toString() : string
    {
        return $this->datetime->format('Y-m-d');
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
        return new self($this->datetime->modify($modify)->setTime(0, 0, 0, 0));
    }
    
    
    public function isPast() : bool
    {
        return $this->datetime->getTimestamp() < self::today()->toTimestamp();
    }
    
    
    public function isFuture() : bool
    {
        return $this->datetime->getTimestamp() > self::today()->toTimestamp();
    }
    
    
    public function isToday() : bool
    {
        return $this->datetime->getTimestamp() === self::today()->toTimestamp();
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
    
    
    
}
