<?php declare(strict_types=1);

namespace Tests\Mediagone\Common\Types\System;

use InvalidArgumentException;
use Mediagone\Common\Types\System\Duration;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Common\Types\System\Duration
 */
final class DurationTest extends TestCase
{
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_be_created() : void
    {
        self::assertInstanceOf(Duration::class, Duration::fromSeconds(20));
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
        Duration::fromSeconds($invalidValue);
    }
    
    
    
    //========================================================================================================
    // Conversion tests
    //========================================================================================================
    
    public function test_can_be_converted_to_json() : void
    {
        $age = Duration::fromSeconds(20);
        self::assertSame(20, $age->jsonSerialize());
    }
    
    
    public function test_can_be_cast_to_string() : void
    {
        $age = Duration::fromSeconds(20);
        self::assertSame('20', (string)$age);
    }
    
    
    
    //========================================================================================================
    // Misc
    //========================================================================================================
    
    public function test_can_tell_value_is_valid() : void
    {
        self::assertTrue(Duration::isValueValid(0));
        self::assertTrue(Duration::isValueValid(1));
        self::assertTrue(Duration::isValueValid(20));
        self::assertTrue(Duration::isValueValid(PHP_INT_MAX));
    }
    
    
    public function test_can_tell_non_string_value_is_invalid() : void
    {
        self::assertFalse(Duration::isValueValid(PHP_INT_MIN));
        self::assertFalse(Duration::isValueValid(-1));
        self::assertFalse(Duration::isValueValid('20'));
        self::assertFalse(Duration::isValueValid(true));
    }
    
    
    
}
