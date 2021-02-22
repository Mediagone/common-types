<?php declare(strict_types=1);

namespace Tests\Mediagone\Common\Types\Text;

use InvalidArgumentException;
use Mediagone\Common\Types\Text\Hex;
use PHPUnit\Framework\TestCase;
use function hex2bin;
use function json_encode;
use function range;
use function str_split;
use function strtolower;


/**
 * @covers \Mediagone\Common\Types\Text\Hex
 */
final class HexTest extends TestCase
{
    //========================================================================================================
    // Creation
    //========================================================================================================
    
    public function test_can_contains_lowercase_hex_char() : void
    {
        foreach (['a','b','c','d','e','f'] as $char) {
            self::assertInstanceOf(Hex::class, Hex::fromString($char));
        }
    }
    
    
    public function test_can_contains_uppercase_hex_char() : void
    {
        foreach (['A','B','C','D','E','F'] as $char) {
            self::assertInstanceOf(Hex::class, Hex::fromString($char));
        }
    }
    
    
    public function test_can_contains_digits() : void
    {
        foreach (['0','1','2','3','4','5', '6', '7', '8', '9'] as $char) {
            self::assertInstanceOf(Hex::class, Hex::fromString($char));
        }
    }
    
    
    public function test_can_contains_multiple_chars() : void
    {
        self::assertInstanceOf(Hex::class, Hex::fromString('abcdefABCDEF0123456789'));
    }
    
    
    /**
     * @dataProvider invalidHexCharProvider
     */
    public function test_cannot_contains_invalid_hex_char($char) : void
    {
        $this->expectException(InvalidArgumentException::class);
        Hex::fromString($char);
    }
    
    public function invalidHexCharProvider()
    {
        yield str_split('ghijklmnopqrstuvwxyzéè-+_#@=$*ù', 1);
    }
    
    
    /**
     * @dataProvider hexLengthProvider
     */
    public function test_can_generate_a_random_value_with_specified_length($length) : void
    {
        self::assertInstanceOf(Hex::class, Hex::random($length));
    }
    
    public function hexLengthProvider()
    {
        yield range(1, 10);
    }
    
    
    public function test_cannot_generate_a_random_value_with_zero_length() : void
    {
        $this->expectException(InvalidArgumentException::class);
        Hex::random(0);
    }
    
    public function test_cannot_generate_a_random_value_with_negative_length() : void
    {
        $this->expectException(InvalidArgumentException::class);
        Hex::random(-1);
    }
    
    
    public function test_can_be_created_from_binary() : void
    {
        $hex = Hex::fromBinary(hex2bin('dede64f400'));
        
        self::assertSame('dede64f400', (string)$hex);
    }
    
    
    
    //========================================================================================================
    // Conversion
    //========================================================================================================
    
    public function test_can_be_encoded_to_json() : void
    {
        $hex = Hex::fromString('dede64f400');
        
        self::assertSame('"dede64f400"', json_encode($hex->jsonSerialize()));
    }
    
    
    public function test_can_be_cast_to_string() : void
    {
        $value = 'dede64f400';
        self::assertSame($value, (string)Hex::fromString($value));
    }
    
    
    public function test_can_be_returned_as_string() : void
    {
        $hex = Hex::fromString('dede64f400');
        
        self::assertSame('dede64f400', (string)$hex);
    }
    
    
    public function test_can_be_returned_as_binary() : void
    {
        $hex = Hex::fromString('dede64f400');
        
        self::assertSame(hex2bin('dede64f400'), $hex->toBinary());
    }
    
    
    public function test_can_return_length() : void
    {
        self::assertSame(6, Hex::fromString('dede64')->getLength());
        self::assertSame(9, Hex::fromString('dede64f40')->getLength());
    }
    
    
    
    //========================================================================================================
    // Misc
    //========================================================================================================
    
    public function test_is_lowercased() : void
    {
        $value = 'DEDE64F400';
        self::assertSame(strtolower($value), (string)Hex::fromString($value));
    }
    
    
    public function test_can_tell_value_is_valid() : void
    {
        self::assertTrue(Hex::isValueValid('dede64f400'));
    }
    
    
    public function test_can_tell_non_string_value_is_invalid() : void
    {
        self::assertFalse(Hex::isValueValid(100));
        self::assertFalse(Hex::isValueValid(true));
    }
    
    
    
}
