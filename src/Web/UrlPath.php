<?php declare(strict_types=1);

namespace Mediagone\Common\Types\Web;

use InvalidArgumentException;
use Mediagone\Common\Types\ValueObject;
use function is_string;
use function strlen;


/**
 * Represents the path part of an Url (eg. /some/path/to/resource.txt)
 * 
 * The value must match the following properties:
 *      - Max length : 600 chars
 *      - The value must start with a slash "/"
 *      - Can contain letters and digits and the following chars: @'!$&()[]*+-_~,.=;:/?%#
 */
final class UrlPath implements ValueObject
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    public const MAX_LENGTH = 600;
    
    
    
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private string $path;
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    private function __construct(string $path)
    {
        if (! self::isValueValid($path)) {
            throw new InvalidArgumentException('The supplied host path is invalid, got "' . $path . '".');
        }
        
        $this->path = $path;
    }
    
    
    /**
     * Creates a new instance from the given string.
     */
    public static function fromString(string $url) : self
    {
        return new self($url);
    }
    
    
    
    //========================================================================================================
    // Static methods
    //========================================================================================================
    
    /**
     * Returns whether the given value is a valid URL's path.
     *
     * @param string $url
     */
    public static function isValueValid($url) : bool
    {
        if (! is_string($url)) {
            return false;
        }
        
        if (strlen($url) > self::MAX_LENGTH) {
            return false;
        }
        
        $regex = '#^' . Url::PATH_PATTERN . '$#i';
        
        return preg_match($regex, $url) === 1;
    }
    
    
    
    //========================================================================================================
    // Methods
    //========================================================================================================
    
    public function jsonSerialize()
    {
        return $this->path;
    }
    
    
    public function toString() : string
    {
        return $this->path;
    }
    
    
    
}
