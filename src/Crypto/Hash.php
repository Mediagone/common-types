<?php declare(strict_types=1);

namespace Mediagone\Common\Types\Crypto;

use LogicException;
use Mediagone\Common\Types\ValueObject;


/**
 * Represents a generic encrypted hash.
 */
abstract class Hash implements ValueObject
{
    public static function fromHash(string $hash) : self
    {
        $infos = password_get_info($hash);
        
        switch ($infos['algo']) {
            case '2y':
                return HashBcrypt::fromHash($hash);
        }
        
        throw new LogicException('This hash algorithm('.$infos['algo'].') is not supported.');
    }
    
    abstract public static function fromString(string $plainString, array $options = []) : self;
    
    abstract public function verifyString(string $plainString) : bool;
    
    abstract public function toString() : string;
}
