<?php declare(strict_types=1);

namespace Tests\Mediagone\Common\Types\System;

use InvalidArgumentException;
use Mediagone\Common\Types\System\Count;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Common\Types\System\Count
 */
final class CountTest extends TestCase
{
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_be_created() : void
    {
        self::assertInstanceOf(Count::class, Count::fromInt(20));
    }
    
    
    
    /**
     * @dataProvider invalidValueProvider
     */
    public function test_cannot_be_created_from_invalid_value($invalidValue) : void
    {
        $this->expectException(InvalidArgumentException::class);
        Count::fromInt($invalidValue);
    }
    
    public function invalidValueProvider()
    {
        yield [PHP_INT_MIN];
        yield [-20];
        yield [-1];
    }
    
    
    
    //========================================================================================================
    // Conversion tests
    //========================================================================================================
    
    public function test_can_be_converted_to_json() : void
    {
        self::assertSame(20, Count::fromInt(20)->jsonSerialize());
    }
    
    
    public function test_can_be_cast_to_string() : void
    {
        self::assertSame('20', (string)Count::fromInt(20));
    }
    
    
    public function test_can_be_cast_to_integer() : void
    {
        self::assertSame(20, Count::fromInt(20)->toInteger());
    }
    
    
    
    //========================================================================================================
    // Misc
    //========================================================================================================
    
    public function test_can_tell_value_is_valid() : void
    {
        self::assertTrue(Count::isValueValid(0));
        self::assertTrue(Count::isValueValid(1));
        self::assertTrue(Count::isValueValid(20));
        self::assertTrue(Count::isValueValid(PHP_INT_MAX));
    }
    
    
    public function test_can_tell_non_string_value_is_invalid() : void
    {
        self::assertFalse(Count::isValueValid(PHP_INT_MIN));
        self::assertFalse(Count::isValueValid(-1));
        self::assertFalse(Count::isValueValid('20'));
        self::assertFalse(Count::isValueValid(true));
    }
    
    
    
}
