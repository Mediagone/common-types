<?php declare(strict_types=1);

namespace Mediagone\Common\Types;

use JsonSerializable;


interface ValueObject extends JsonSerializable
{
    /**
     * Returns whether the specified value is valid for the given object.
     */
    public static function isValueValid($value) : bool;
    
    public function __toString() : string;
    
}
