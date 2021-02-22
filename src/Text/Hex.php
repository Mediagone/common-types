<?php declare(strict_types=1);

namespace Mediagone\Common\Types\Text;

use InvalidArgumentException;
use Mediagone\Common\Types\ValueObject;
use function bin2hex;
use function ceil;
use function hex2bin;
use function is_string;
use function preg_match;
use function random_bytes;
use function strlen;
use function strtolower;
use function substr;


final class Hex implements ValueObject
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private string $hex;
    
    
    
    //========================================================================================================
    // Constructor & Factory methods
    //========================================================================================================
    
    private function __construct(string $hex)
    {
        if (! self::isValueValid($hex)) {
            throw new InvalidArgumentException('Invalid hexadecimal value for ' . self::class . " ($hex), it must only contains A-F or 0-9 chars.");
        }
        
        $this->hex = strtolower($hex);
    }
    
    
    public static function random(int $length) : self
    {
        if ($length < 1) {
            throw new InvalidArgumentException("Generating a random hex string requires a length > 0 (got $length).");
        }
        
        $numberOfBytes = (int)ceil((float)$length / 2);
        
        $hex = bin2hex(random_bytes($numberOfBytes));
        
        // Note: random_bytes() always generates even-length strings, then we need to truncate it to get an odd length hexadecimal string.
        $hex = substr($hex, 0, $length);
        
        return new self($hex);
    }
    
    
    public static function fromString(string $hex) : self
    {
        return new self($hex);
    }
    
    
    /**
     * Creates a new instance from the given binary string representation.
     */
    public static function fromBinary(string $binaryString)
    {
        return new self(bin2hex($binaryString));
    }
    
    
    
    //========================================================================================================
    // Static methods
    //========================================================================================================
    
    public static function isValueValid($value) : bool
    {
        if (! is_string($value)) {
            return false;
        }
        
        return preg_match('#^[abcdef0-9]+$#i', $value) === 1;
    }
    
    
    
    //========================================================================================================
    // Methods
    //========================================================================================================
    
    public function jsonSerialize()
    {
        return $this->hex;
    }
    
    
    public function __toString() : string
    {
        return $this->hex;
    }
    
    
    public function toBinary() : string
    {
        return hex2bin($this->hex);
    }
    
    
    public function getLength() : int
    {
        return strlen($this->hex);
    }
    
    
    
}
