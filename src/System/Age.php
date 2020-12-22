<?php declare(strict_types=1);

namespace Mediagone\Common\Types\System;

use InvalidArgumentException;
use Mediagone\Common\Types\ValueObject;
use function is_int;


/**
 * Represents an Age value (must be a positive integer, or zero).
 */
final class Age implements ValueObject
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private int $age;
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    private function __construct(int $age)
    {
        if (! self::isValueValid($age)) {
            throw new InvalidArgumentException("The supplied age ($age) is invalid.");
        }
        
        $this->age = $age;
    }
    
    
    public static function fromInt(int $age) : self
    {
        return new self($age);
    }
    
    
    
    //========================================================================================================
    // Static methods
    //========================================================================================================
    
    /**
     * Returns whether the given value is a valid Age.
     *
     * @param int $age
     */
    public static function isValueValid($age) : bool
    {
        if (! is_int($age)) {
            return false;
        }
        
        return $age >= 0;
    }
    
    
    
    //========================================================================================================
    // Methods
    //========================================================================================================
    
    public function jsonSerialize()
    {
        return $this->age;
    }
    
    
    public function toInteger() : int
    {
        return $this->age;
    }
    
    
    
}
