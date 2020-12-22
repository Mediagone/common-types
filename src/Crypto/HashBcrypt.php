<?php declare(strict_types=1);

namespace Mediagone\Common\Types\Crypto;

use InvalidArgumentException;
use Mediagone\Common\Types\ValueObject;
use function is_string;


/**
 * Represents a Bcrypt encrypted hash (version 2 not supported for security reasons).
 */
final class HashBcrypt implements ValueObject
{
    //========================================================================================================
    // Constants & Properties
    //========================================================================================================
    
    public const DEFAULT_COST = 14;
    
    private string $hash;
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    private function __construct(string $hash)
    {
        if (! self::isValueValid($hash)) {
            throw new InvalidArgumentException('The supplied value is not a valid Bcrypt hash.');
        }
        
        $this->hash = $hash;
    }
    
    
    public static function fromHash(string $hash) : self
    {
        return new self($hash);
    }
    
    
    /**
     * Encrypts the given string and creates a bcrypt hash from it.
     */
    public static function fromString(string $plainString, int $cost = self::DEFAULT_COST) : self
    {
        if ($cost < 11) {
            throw new InvalidArgumentException('For security reasons, Cost factor must be grater than 10 (14 recommended)');
        }
        if ($cost > 30) {
            throw new InvalidArgumentException('Cost factor looks abnormally high (14 recommended, 30 max)');
        }
        
        $hash = password_hash($plainString, PASSWORD_BCRYPT, ['cost' => $cost]);
        
        return new self($hash);
    }
    
    
    
    
    //========================================================================================================
    // Static methods
    //========================================================================================================
    
    public static function isValueValid($hash) : bool
    {
        if (! is_string($hash)) {
            return false;
        }
        
        $regex = '#^'
            . '\$2(a|y)\$' // hashing algorithm version (not supporting the $2$ version)
            . '[0-9]{2}\$' // cost parameter
            . '[\./0-9a-zA-Z]{53}' // a 53 characters long base-64-encoded value (custom alphabet)
            . '$#';
        
        return preg_match($regex, $hash) === 1;
    }
    
    //========================================================================================================
    // Methods
    //========================================================================================================
    
    public function jsonSerialize()
    {
        return $this->hash;
    }
    
    
    public function toString() : string
    {
        return $this->hash;
    }
    
    
    
}