<?php declare(strict_types=1);

namespace Mediagone\Common\Types\Text;

use InvalidArgumentException;
use Mediagone\Common\Types\ValueObject;
use function is_string;
use function strlen;


/**
 * Represents a medium length Text string (~16 Mb max).
 */
final class TextMedium implements ValueObject
{
    //========================================================================================================
    // Constants
    //========================================================================================================
    
    public const MAX_LENGTH = 16777215;
    
    
    
    //========================================================================================================
    //
    //========================================================================================================
    
    private string $text;
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    private function __construct(string $text)
    {
        if (! self::isValueValid($text)) {
            throw new InvalidArgumentException('The supplied TextMedium is invalid.');
        }
        
        $this->text = $text;
    }
    
    
    public static function fromString(string $text) : self
    {
        return new self($text);
    }
    
    
    
    //========================================================================================================
    // Static methods
    //========================================================================================================
    
    /**
     * Returns whether the given value is a valid Text.
     */
    public static function isValueValid($text) : bool
    {
        return is_string($text) && strlen($text) <= self::MAX_LENGTH;
    }
    
    
    
    //========================================================================================================
    // Methods
    //========================================================================================================
    
    public function jsonSerialize()
    {
        return $this->text;
    }
    
    
    public function toString() : string
    {
        return $this->text;
    }
    
    
    
}
