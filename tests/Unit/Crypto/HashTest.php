<?php declare(strict_types=1);

namespace Tests\Mediagone\Common\Types\Crypto;

use Mediagone\Common\Types\Crypto\Hash;
use Mediagone\Common\Types\Crypto\HashArgon2id;
use Mediagone\Common\Types\Crypto\HashBcrypt;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Common\Types\Crypto\Hash
 */
final class HashTest extends TestCase
{
    //========================================================================================================
    // Factory
    //========================================================================================================
    
    public function test_can_create_from_a_bcrypt_hash() : void
    {
        $hash = HashBcrypt::fromString('p4ssword')->toString();
        self::assertInstanceOf(HashBcrypt::class, Hash::fromHash($hash));
    }
    
    
    public function test_can_create_from_a_argon2id_hash() : void
    {
        $hash = HashArgon2id::fromString('p4ssword')->toString();
        self::assertInstanceOf(HashArgon2id::class, Hash::fromHash($hash));
    }
    
    
    
}
