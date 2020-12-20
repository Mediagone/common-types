<?php declare(strict_types=1);

namespace Mediagone\Common\Types\Text;

use InvalidArgumentException;
use Mediagone\Common\Types\ValueObject;
use function is_string;
use function preg_match;
use function strlen;


/**
 * Represents a Slug string.
 *
 * The value must match the following properties:
 *      - 1 to 200 chars long
 *      - can contain lowercase letters and digits
 *      - can contain hyphens (not consecutive)
 */
final class Slug implements ValueObject
{
    //========================================================================================================
    // Constants
    //========================================================================================================
    
    public const MAX_LENGTH = 200;
    
    
    
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private string $slug;
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    private function __construct(string $slug)
    {
        if (! self::isValueValid($slug)) {
            throw new InvalidArgumentException("The supplied slug ($slug) is invalid.");
        }
        
        $this->slug = $slug;
    }
    
    
    /**
     * Creates a new instance from the given string.
     */
    public static function fromString(string $slug) : self
    {
        return new self($slug);
    }
    
    
    
    //========================================================================================================
    // Static methods
    //========================================================================================================
    
    /**
     * Returns whether the given value is a valid slug.
     *
     * @param string $slug
     */
    public static function isValueValid($slug) : bool
    {
        if (! is_string($slug)) {
            return false;
        }
        
        if (strlen($slug) > self::MAX_LENGTH) {
            return false;
        }
        
        $regex = '#^'
            . '[a-z0-9]+' // starts with a letter or digit
            . '(-?' // allows hyphens (not alongside each other)
            . '[a-z0-9]+)*' // ends with letters or digits
            . '$#';
        
        return preg_match($regex, $slug) === 1;
    }
    
    
    
    //========================================================================================================
    // Methods
    //========================================================================================================
    
    public function jsonSerialize()
    {
        return $this->slug;
    }
    
    
    public function __toString() : string
    {
        return $this->slug;
    }
    
    
    
}
