<?php declare(strict_types=1);

namespace Tests\Mediagone\Common\Types\Crypto;

use InvalidArgumentException;
use JsonSerializable;
use Mediagone\Common\Types\Crypto\HashBcrypt;
use PHPUnit\Framework\TestCase;
use function str_repeat;


/**
 * @covers \Mediagone\Common\Types\Crypto\HashBcrypt
 */
final class HashBcryptTest extends TestCase
{
    //========================================================================================================
    // Instantiation
    //========================================================================================================
    
    public function test_can_be_created_from_valid_string() : void
    {
        $hash = HashBcrypt::fromString('p4ssword');
        self::assertInstanceOf(HashBcrypt::class, $hash);
        self::assertSame(HashBcrypt::DEFAULT_COST, $hash->getCost());
    }
    
    
    public function test_can_be_created_from_valid_string_with_cost() : void
    {
        $hash = HashBcrypt::fromString('p4ssword', ['cost' => HashBcrypt::DEFAULT_COST - 1]);
        self::assertInstanceOf(HashBcrypt::class, $hash);
        self::assertSame(HashBcrypt::DEFAULT_COST - 1, $hash->getCost());
    }
    
    
    public function test_can_be_created_from_valid_hash() : void
    {
        $hash = HashBcrypt::fromHash('$2y$14$mw8QPR5lg/SAgaO/qCOLx.asn.mUQXfbhyTkvxsI5SyfkWgpiGLhm');
        self::assertInstanceOf(HashBcrypt::class, $hash);
    }
    
    
    public function test_cannot_be_created_from_empty_hash() : void
    {
        $this->expectException(InvalidArgumentException::class);
        HashBcrypt::fromHash('');
    }
    
    
    
    //========================================================================================================
    // Version Tests
    //========================================================================================================
    
    public function test_hash_version_cannot_be_empty() : void
    {
        $this->expectException(InvalidArgumentException::class);
        HashBcrypt::fromHash('$$12$00000000000000000000000000000000000000000000000000000');
    }
    
    
    public function test_hash_version_2_is_not_supported() : void
    {
        $this->expectException(InvalidArgumentException::class);
        HashBcrypt::fromHash('$2$12$00000000000000000000000000000000000000000000000000000');
    }
    
    
    public function test_hash_version_2a_is_supported() : void
    {
        self::assertInstanceOf(HashBcrypt::class, HashBcrypt::fromHash('$2a$12$00000000000000000000000000000000000000000000000000000'));
    }
    
    
    public function test_version_2y_is_supported() : void
    {
        self::assertInstanceOf(HashBcrypt::class, HashBcrypt::fromHash('$2y$12$00000000000000000000000000000000000000000000000000000'));
    }
    
    
    
    
    //========================================================================================================
    // String with Cost
    //========================================================================================================
    
    public function test_hash_cost_cannot_be_too_low() : void
    {
        $this->expectException(InvalidArgumentException::class);
        HashBcrypt::fromString('p4ssword', ['cost' => 10]);
    }
    
    public function test_hash_cost_cannot_be_too_high() : void
    {
        $this->expectException(InvalidArgumentException::class);
        HashBcrypt::fromString('p4ssword', ['cost' => 31]);
    }
    
    
    
    //========================================================================================================
    // Hash with Cost
    //========================================================================================================
    
    public function test_hash_cost_cannot_be_empty() : void
    {
        $this->expectException(InvalidArgumentException::class);
        HashBcrypt::fromHash('$2a$$00000000000000000000000000000000000000000000000000000');
    }
    
    public function test_hash_cost_cannot_be_1_char_long() : void
    {
        $this->expectException(InvalidArgumentException::class);
        HashBcrypt::fromHash('$2a$1$00000000000000000000000000000000000000000000000000000');
    }
    
    
    public function test_hash_cost_must_be_2_chars_long() : void
    {
        self::assertInstanceOf(HashBcrypt::class, HashBcrypt::fromHash('$2y$12$00000000000000000000000000000000000000000000000000000'));
    }
    
    
    public function test_hash_cost_cannot_be_3_chars_long() : void
    {
        $this->expectException(InvalidArgumentException::class);
        HashBcrypt::fromHash('$2a$123$00000000000000000000000000000000000000000000000000000');
    }
    
    
    
    //========================================================================================================
    // Hash Tests
    //========================================================================================================
    
    public function test_hash_must_be_53_chars_long() : void
    {
        $hash53charsLong = '$2a$12$' . str_repeat('0', 53);
        self::assertInstanceOf(HashBcrypt::class, HashBcrypt::fromHash($hash53charsLong));
    }
    
    
    public function test_hash_cannot_be_shorter_than_53_chars_long() : void
    {
        $this->expectException(InvalidArgumentException::class);
        $tooShortHash = '$2a$12$' . str_repeat('0', 52);
        HashBcrypt::fromHash($tooShortHash);
    }
    
    
    public function test_hash_cannot_be_longer_than_53_chars_long() : void
    {
        $this->expectException(InvalidArgumentException::class);
        $tooLongHash = '$2a$12$' . str_repeat('0', 54);
        HashBcrypt::fromHash($tooLongHash);
    }
    
    
    public function test_hash_can_contain_letters() : void
    {
        self::assertInstanceOf(HashBcrypt::class, HashBcrypt::fromHash('$2a$12$abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZA'));
    }
    
    
    public function test_hash_can_contain_digits() : void
    {
        self::assertInstanceOf(HashBcrypt::class, HashBcrypt::fromHash('$2a$12$01234567890123456789012345678901234567890123456789000'));
    }
    
    
    public function test_hash_cannot_contain_plus_char() : void
    {
        $this->expectException(InvalidArgumentException::class);
        HashBcrypt::fromHash('$2a$12$000000000000000000000000+0000000000000000000000000000');
        //self::assertInstanceOf(HashBcrypt::class, new HashBcrypt('$2a$12$abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMOPQRSTUVWXYZ0123456789./'));
    }
    
    
    public function test_hash_cannot_contain_equal_char() : void
    {
        $this->expectException(InvalidArgumentException::class);
        HashBcrypt::fromHash('$2a$12$000000000000000000000000=0000000000000000000000000000');
        //self::assertInstanceOf(HashBcrypt::class, new HashBcrypt('$2a$12$abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMOPQRSTUVWXYZ0123456789./'));
    }
    
    
    public function test_hash_can_contain_slash() : void
    {
        self::assertInstanceOf(HashBcrypt::class, HashBcrypt::fromHash('$2a$12$000000000000000000000000/0000000000000000000000000000'));
    }
    
    
    public function test_hash_can_contain_dot() : void
    {
        self::assertInstanceOf(HashBcrypt::class, HashBcrypt::fromHash('$2a$12$000000000000000000000000.0000000000000000000000000000'));
    }
    
    
    
    //========================================================================================================
    // Conversion
    //========================================================================================================
    
    public function test_can_be_encoded_to_json() : void
    {
        $hash = HashBcrypt::fromString('password');
        
        self::assertInstanceOf(JsonSerializable::class, $hash);
        self::assertMatchesRegularExpression('#^\$2y\$[0-9]{1,2}\$.{53}$#', json_decode(json_encode($hash->jsonSerialize())));
    }
    
    
    public function test_can_be_cast_to_string() : void
    {
        $hash = HashBcrypt::fromString('password');
        
        self::assertMatchesRegularExpression('#^\$2y\$[0-9]{1,2}\$.{53}$#', $hash->toString());
    }
    
    
    
    //========================================================================================================
    // Misc
    //========================================================================================================
    
    public function test_can_tell_value_is_valid() : void
    {
        self::assertTrue(HashBcrypt::isValueValid('$2y$14$rZkWv2E80hF1Hk6KsTsU7.bNoB/98/tLD5ru5wmACnT0bSUYNqH2a'));
    }
    
    
    public function test_can_tell_non_string_value_is_invalid() : void
    {
        self::assertFalse(HashBcrypt::isValueValid(100));
        self::assertFalse(HashBcrypt::isValueValid(true));
    }
    
    
    
    //========================================================================================================
    // Verify
    //========================================================================================================
    
    public function test_can_verify_valid_password() : void
    {
        $hash = HashBcrypt::fromString('p4ssword');
        self::assertTrue($hash->verifyString('p4ssword'));
    }
    
    
    public function test_can_verify_invalid_password() : void
    {
        $hash = HashBcrypt::fromString('p4ssword');
        self::assertFalse($hash->verifyString('not-the-same'));
    }
    
    
    
}
