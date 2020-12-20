<?php declare(strict_types=1);

namespace Mediagone\Common\Types\Text;

use InvalidArgumentException;
use Mediagone\Common\Types\ValueObject;
use function is_string;
use function preg_match;
use function trim;


/**
 * Represents a Name string.
 *
 * The value must match the following properties:
 *      - 0 to 50 chars long
 *      - can contain letters (with accents)
 *      - can contain hyphens
 *      - can contain spaces
 *      - can contain apostrophe
 */
final class Name implements ValueObject
{
    //========================================================================================================
    // Constants
    //========================================================================================================
    
    public const MAX_LENGTH = 50;
    
    public const REGEX_CHAR = "[- 'a-zA-ZÀ-ÖØ-öø-ÿ]";
    
    public const REGEX = '#^'.self::REGEX_CHAR.'{0,'.self::MAX_LENGTH.'}$#';
    
    
    
    //========================================================================================================
    //
    //========================================================================================================
    
    private string $name;
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    private function __construct(string $name)
    {
        $name = trim($name);
        
        if (! self::isValueValid($name)) {
            throw new InvalidArgumentException("The supplied name ($name) is invalid.");
        }
        
        $this->name = $name;
    }
    
    
    /**
     * Creates a new instance from the given string.
     */
    public static function fromString(string $name) : self
    {
        return new self($name);
    }
    
    
    
    //========================================================================================================
    // Static methods
    //========================================================================================================
    
    /**
     * Returns whether the given value is a valid Name.
     *
     * @param string $name
     */
    public static function isValueValid($name) : bool
    {
        if (! is_string($name)) {
            return false;
        }
        
        return preg_match(self::REGEX, $name) === 1;
    }
    
    
    
    //========================================================================================================
    // Methods
    //========================================================================================================
    
    public function jsonSerialize()
    {
        return $this->name;
    }
    
    
    public function __toString() : string
    {
        return $this->name;
    }
    
    
    
}
