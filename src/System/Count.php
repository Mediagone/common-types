<?php declare(strict_types=1);

namespace Mediagone\Common\Types\System;

use InvalidArgumentException;
use Mediagone\Common\Types\ValueObject;
use function is_int;


/**
 * Represents a Count (must be an positive integer, or zero).
 */
final class Count implements ValueObject
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private int $count;
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    private function __construct(int $count)
    {
        if (! self::isValueValid($count)) {
            throw new InvalidArgumentException("The supplied count ($count) is invalid.");
        }
        
        $this->count = $count;
    }
    
    
    public static function fromInt(int $count) : self
    {
        return new self($count);
    }
    
    
    
    //========================================================================================================
    // Static methods
    //========================================================================================================
    
    /**
     * Returns whether the given value is a valid Count.
     *
     * @param int $count
     */
    public static function isValueValid($count) : bool
    {
        if (! is_int($count)) {
            return false;
        }
        
        return $count >= 0;
    }
    
    
    
    //========================================================================================================
    // Methods
    //========================================================================================================
    
    public function jsonSerialize()
    {
        return $this->count;
    }
    
    
    public function __toString() : string
    {
        return (string)$this->count;
    }
    
    
    public function toInteger() : int
    {
        return $this->count;
    }
    
    
    
}
