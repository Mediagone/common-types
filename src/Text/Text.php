<?php declare(strict_types=1);

namespace Mediagone\Common\Types\Text;

use InvalidArgumentException;
use Mediagone\Common\Types\ValueObject;
use function is_string;
use function strlen;


/**
 * Represents a Text string (~64 Kb max).
 */
final class Text implements ValueObject
{
    //========================================================================================================
    // Constants
    //========================================================================================================
    
    public const MAX_LENGTH = 65535;
    
    
    
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
            throw new InvalidArgumentException('The supplied Text is invalid.');
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
    
    
    public function __toString() : string
    {
        return $this->text;
    }
    
    
    
}
