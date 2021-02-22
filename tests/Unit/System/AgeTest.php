<?php declare(strict_types=1);

namespace Tests\Mediagone\Common\Types\System;

use InvalidArgumentException;
use Mediagone\Common\Types\System\Age;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Common\Types\System\Age
 */
final class AgeTest extends TestCase
{
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_be_created() : void
    {
        self::assertInstanceOf(Age::class, Age::fromInt(20));
    }
    
    
    
    public function invalidValueProvider()
    {
        yield [PHP_INT_MIN];
        yield [-20];
        yield [-1];
    }
    
    /**
     * @dataProvider invalidValueProvider
     */
    public function test_cannot_be_created_from_invalid_value($invalidValue) : void
    {
        $this->expectException(InvalidArgumentException::class);
        Age::fromInt($invalidValue);
    }
    
    
    
    //========================================================================================================
    // Conversion tests
    //========================================================================================================
    
    public function test_can_be_converted_to_json() : void
    {
        $age = Age::fromInt(20);
        self::assertSame(20, $age->jsonSerialize());
    }
    
    
    public function test_can_be_cast_to_string() : void
    {
        $age = Age::fromInt(20);
        self::assertSame('20', (string)$age);
    }
    
    
    public function test_can_be_cast_to_integer() : void
    {
        $age = Age::fromInt(20);
        self::assertSame(20, $age->toInteger());
    }
    
    
    
    //========================================================================================================
    // Misc
    //========================================================================================================
    
    public function test_can_tell_value_is_valid() : void
    {
        self::assertTrue(Age::isValueValid(0));
        self::assertTrue(Age::isValueValid(1));
        self::assertTrue(Age::isValueValid(20));
        self::assertTrue(Age::isValueValid(PHP_INT_MAX));
    }
    
    
    public function test_can_tell_non_string_value_is_invalid() : void
    {
        self::assertFalse(Age::isValueValid(PHP_INT_MIN));
        self::assertFalse(Age::isValueValid(-1));
        self::assertFalse(Age::isValueValid('20'));
        self::assertFalse(Age::isValueValid(true));
    }
    
    
    
}
