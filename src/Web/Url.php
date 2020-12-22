<?php declare(strict_types=1);

namespace Mediagone\Common\Types\Web;

use InvalidArgumentException;
use Mediagone\Common\Types\ValueObject;
use function is_string;
use function mb_strlen;
use function preg_match;


/**
 * Represents an Url, but DOESN'T strictly follow RFC 3986.
 *
 * The value must match the following pattern: {scheme}://{domain}/{path}
 *      - max length : 1500 chars
 * 
 * Each part must match the following properties:
 *      {scheme}:
 *          - http or https
 *      {domain}:
 *          - can contain letters and digits
 *          - can have up to 10 subdomains + 1 extension (separated with a dot)
 *          - can contain hyphens (not consecutive)
 *          - must start and end with a letter or a digit
 *      {path}:
 *          - can contain letters and digits and the following chars: -.#[]'@!$&()*+,;=_~:/?%
 */
final class Url implements ValueObject
{
    //========================================================================================================
    // Constants
    //========================================================================================================
    
    public const MAX_LENGTH = 1500;
    
    public const SCHEME_MAX_LENGTH = 8 ;
    public const SCHEME_PATTERN = '(?<scheme>(?:http|https)://)';
    
    public const DOMAIN_MAX_LENGTH = 255 - self::SCHEME_MAX_LENGTH;
    public const DOMAIN_PATTERN = '(?<domain>'
        . '(?:[a-z0-9]+(?:-[a-z0-9]+)*)'
        . '(?:\.(?:[a-z0-9]+(?:-[a-z0-9]+)*)){1,11}' // max 10 subdomains (arbitrary limit) + 1 extension
        . ')';
    
    public const PATH_MAX_LENGTH = self::MAX_LENGTH - self::SCHEME_MAX_LENGTH - self::DOMAIN_MAX_LENGTH;
    public const PATH_PATTERN = '(?:/['
        . 'abcdefghijklmnopqrstuvwxyz' // allow letters
        . '0123456789' // allow digits
        . '\-' // allow -
        . '\.' // allow .
        . '\#' // allow #
        . '\[\]' // allow []
        . "'" // allow '
        . '@!$&()*+,;=_~:/?%' // allow miscellaneous chars
        . ']*)';
    
    
    
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private string $url;
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    private function __construct(string $url)
    {
        if (! self::isValueValid($url)) {
            throw new InvalidArgumentException('The supplied url is invalid, got "' . $url . '".');
        }
        
        $this->url = $url;
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
     * Returns whether the given value is a valid URL.
     *
     * @param string $url
     */
    public static function isValueValid($url) : bool
    {
        if (! is_string($url)) {
            return false;
        }
        
        if (mb_strlen($url) > self::MAX_LENGTH) {
            return false;
        }
        
        $regex = '#^'
            . self::SCHEME_PATTERN
            . self::DOMAIN_PATTERN
            . '(?<path>' . self::PATH_PATTERN . '*)'
            . '$#i';
        
        if (preg_match($regex, $url, $matches) !== 1) {
            return false;
        }
        
        if (mb_strlen($matches['domain']) > self::DOMAIN_MAX_LENGTH) {
            return false; // full domain name too long
        }
        
        return true;
    }
    
    
    
    //========================================================================================================
    // Methods
    //========================================================================================================
    
    public function jsonSerialize()
    {
        return $this->url;
    }
    
    
    public function toString() : string
    {
        return $this->url;
    }
    
    
    
}
