<?php declare(strict_types=1);

namespace Tests\Mediagone\Common\Types\Crypto;

use Generator;
use InvalidArgumentException;
use JsonSerializable;
use Mediagone\Common\Types\Crypto\HashArgon2id;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Common\Types\Crypto\HashArgon2id
 */
final class HashArgon2idTest extends TestCase
{
    //========================================================================================================
    // Instantiation
    //========================================================================================================
    
    public function test_can_be_created_from_valid_string() : void
    {
        $hash = HashArgon2id::fromString('p4ssword');
        self::assertInstanceOf(HashArgon2id::class, $hash);
        self::assertSame(HashArgon2id::DEFAULT_MEMORY_COST, $hash->getMemoryCost());
        self::assertSame(HashArgon2id::DEFAULT_TIME_COST, $hash->getTimeCost());
        self::assertSame(HashArgon2id::DEFAULT_THREADS, $hash->getThreadsCount());
    }
    
    public function test_can_be_created_from_valid_string_with_options() : void
    {
        $hash = HashArgon2id::fromString('p4ssword', [
            'memory_cost' => HashArgon2id::DEFAULT_MEMORY_COST - 1024,
            'time_cost' => HashArgon2id::DEFAULT_TIME_COST - 1,
            'threads' => HashArgon2id::DEFAULT_THREADS + 1,
        ]);
        self::assertInstanceOf(HashArgon2id::class, $hash);
        self::assertSame(HashArgon2id::DEFAULT_MEMORY_COST - 1024, $hash->getMemoryCost());
        self::assertSame(HashArgon2id::DEFAULT_TIME_COST - 1, $hash->getTimeCost());
        self::assertSame(HashArgon2id::DEFAULT_THREADS + 1, $hash->getThreadsCount());
    }
    
    
    /**
     * @dataProvider validHashProvider
     */
    public function test_can_be_created_from_valid_hash($validHash) : void
    {
        $hash = HashArgon2id::fromHash($validHash);
        self::assertInstanceOf(HashArgon2id::class, $hash);
    }
    
    public function validHashProvider() : Generator
    {
        yield ['$argon2id$v=19$m=65536,t=4,p=1$MGU4dnY2Lkw2bHpmTzV5Wg$u7LBqzixVlVzvWTcbxHGpGTj6FyStwInN67cTGZBNXI'];
        yield ['$argon2id$v=19$m=65536,t=4,p=1$MGU4dnY2Lkw2bHpmTzV5Wg$u7LBqzixVlVzvWTcbxHGpGTj6FyStwInN67cTGZBNX+'];
        yield ['$argon2id$v=19$m=65536,t=4,p=1$MGU4dnY2Lkw2bHpmTzV5Wg$u7LBqzixVlVzvWTcbxHGpGTj6FyStwInN67cTGZBNX/'];
    }
    
    
    public function test_cannot_be_created_from_empty_hash() : void
    {
        $this->expectException(InvalidArgumentException::class);
        HashArgon2id::fromHash('');
    }
    
    
    /**
     * @dataProvider invalidHashProvider
     */
    public function test_cannot_be_created_from_invalid_hash(string $invalidHash, string $reason) : void
    {
        $this->expectException(InvalidArgumentException::class);
        HashArgon2id::fromHash($invalidHash);
    }
    
    
    public function invalidHashProvider() : Generator
    {
        yield ['$$v=19$m=65536,t=4,p=1$MGU4dnY2Lkw2bHpmTzV5Wg$u7LBqzixVlVzvWTcbxHGpGTj6FyStwInN67cTGZBNXI', 'missing algo field'];
        yield ['$argon2$v=19$m=65536,t=4,p=1$MGU4dnY2Lkw2bHpmTzV5Wg$u7LBqzixVlVzvWTcbxHGpGTj6FyStwInN67cTGZBNXI', 'invalid algo field'];
        yield ['$argon2id$v=$m=65536,t=4,p=1$MGU4dnY2Lkw2bHpmTzV5Wg$u7LBqzixVlVzvWTcbxHGpGTj6FyStwInN67cTGZBNXI', 'missing version'];
        yield ['$argon2id$v=19$m=,t=4,p=1$MGU4dnY2Lkw2bHpmTzV5Wg$u7LBqzixVlVzvWTcbxHGpGTj6FyStwInN67cTGZBNXI', 'missing memory cost'];
        yield ['$argon2id$v=19$m=65536,t=,p=1$MGU4dnY2Lkw2bHpmTzV5Wg$u7LBqzixVlVzvWTcbxHGpGTj6FyStwInN67cTGZBNXI', 'missing time cost'];
        yield ['$argon2id$v=19$m=65536,t=4,p=$MGU4dnY2Lkw2bHpmTzV5Wg$u7LBqzixVlVzvWTcbxHGpGTj6FyStwInN67cTGZBNXI', 'missing threads count'];
        yield ['$argon2id$v=19$m=65536,t=4,p=1$MGU4dnY2Lkw2bHpmTzV5W$u7LBqzixVlVzvWTcbxHGpGTj6FyStwInN67cTGZBNXI', 'too short salt'];
        yield ['$argon2id$v=19$m=65536,t=4,p=1$MGU4dnY2Lkw2bHpmTzV5Wgg$u7LBqzixVlVzvWTcbxHGpGTj6FyStwInN67cTGZBNXI', 'too long salt'];
        yield ['$argon2id$v=19$m=65536,t=4,p=1$MGU4dnY2Lkw2bHpmTzV5Wg$u7LBqzixVlVzvWTcbxHGpGTj6FyStwInN67cTGZBNX', 'too short hash'];
        yield ['$argon2id$v=19$m=65536,t=4,p=1$MGU4dnY2Lkw2bHpmTzV5Wg$u7LBqzixVlVzvWTcbxHGpGTj6FyStwInN67cTGZBNXII', 'too long hash'];
    }
    
    
    //========================================================================================================
    // String with Cost
    //========================================================================================================
    
    public function test_memory_cost_cannot_be_too_low() : void
    {
        $this->expectException(InvalidArgumentException::class);
        HashArgon2id::fromString('p4ssword', ['memory_cost' => 10 * 1024 - 1]);
    }
    
    public function test_time_cost_cannot_be_too_low() : void
    {
        $this->expectException(InvalidArgumentException::class);
        HashArgon2id::fromString('p4ssword', ['time_cost' => 3]);
    }
    
    public function test_threads_count_must_be_strictly_positive() : void
    {
        $this->expectException(InvalidArgumentException::class);
        HashArgon2id::fromString('p4ssword', ['threads' => 0]);
    }
    
    
    
    //========================================================================================================
    // Conversion
    //========================================================================================================
    
    public function test_can_be_encoded_to_json() : void
    {
        $value = '$argon2id$v=19$m=65536,t=4,p=1$MGU4dnY2Lkw2bHpmTzV5Wg$u7LBqzixVlVzvWTcbxHGpGTj6FyStwInN67cTGZBNXI';
        $hash = HashArgon2id::fromHash($value);
        
        self::assertInstanceOf(JsonSerializable::class, $hash);
        self::assertSame(json_encode($value), json_encode($hash->jsonSerialize()));
    }
    
    
    public function test_can_be_cast_to_string() : void
    {
        $value = '$argon2id$v=19$m=65536,t=4,p=1$MGU4dnY2Lkw2bHpmTzV5Wg$u7LBqzixVlVzvWTcbxHGpGTj6FyStwInN67cTGZBNXI';
        $hash = HashArgon2id::fromHash($value);
        
        self::assertSame($value, $hash->toString());
    }
    
    
    
    //========================================================================================================
    // Misc
    //========================================================================================================
    
    /**
     * @dataProvider validHashProvider
     */
    public function test_can_tell_hash_is_valid($validHash) : void
    {
        self::assertTrue(HashArgon2id::isValueValid($validHash));
    }
    
    /**
     * @dataProvider invalidHashProvider
     */
    public function test_can_tell_hash_is_invalid($invalidHash) : void
    {
        self::assertFalse(HashArgon2id::isValueValid($invalidHash));
    }
    
    
    public function test_can_tell_non_string_value_is_invalid() : void
    {
        self::assertFalse(HashArgon2id::isValueValid(100));
        self::assertFalse(HashArgon2id::isValueValid(true));
    }
    
    
    
    //========================================================================================================
    // Verify
    //========================================================================================================
    
    public function test_can_verify_valid_password() : void
    {
        $hash = HashArgon2id::fromString('p4ssword');
        self::assertTrue($hash->verifyString('p4ssword'));
    }
    
    
    public function test_can_verify_invalid_password() : void
    {
        $hash = HashArgon2id::fromString('p4ssword');
        self::assertFalse($hash->verifyString('not-the-same'));
    }
    
    
    
}
