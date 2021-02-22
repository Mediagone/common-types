<?php declare(strict_types=1);

namespace Tests\Mediagone\Common\Types\Web;

use InvalidArgumentException;
use Mediagone\Common\Types\Web\UrlHost;
use PHPUnit\Framework\TestCase;
use function json_encode;
use function str_repeat;
use function strlen;


/**
 * @covers \Mediagone\Common\Types\Web\UrlHost
 */
final class UrlHostTest extends TestCase
{
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_be_created() : void
    {
        self::assertInstanceOf(UrlHost::class, UrlHost::fromString('https://domain.com'));
    }
    
    
    public function test_cannot_have_path() : void
    {
        $this->expectException(InvalidArgumentException::class);
        UrlHost::fromString('https://domain.com/about');
    }
    
    
    public function test_cannot_end_with_a_slash() : void
    {
        $this->expectException(InvalidArgumentException::class);
        UrlHost::fromString('https://domain.com/');
    }
    
    
    public function test_cannot_be_too_long() : void
    {
        $longDomain = str_repeat('a', UrlHost::MAX_LENGTH - strlen('https://.com'));
        self::assertInstanceOf(UrlHost::class, UrlHost::fromString("https://$longDomain.com"));
        
        $this->expectException(InvalidArgumentException::class);
        UrlHost::fromString("https://{$longDomain}a.com");
    }
    
    
    
    //========================================================================================================
    // Conversion tests
    //========================================================================================================
    
    public function test_can_be_converted_to_json() : void
    {
        $url = UrlHost::fromString('http://domain.com');
        self::assertSame(json_encode($url), '"http:\/\/domain.com"');
    }
    
    
    public function test_can_be_cast_to_string() : void
    {
        $slug = UrlHost::fromString('http://domain.com');
        self::assertSame('http://domain.com', (string)$slug);
    }
    
    
    
    //========================================================================================================
    // Misc tests
    //========================================================================================================
    
    public function test_can_tell_non_string_value_is_invalid() : void
    {
        self::assertFalse(UrlHost::isValueValid(100));
        self::assertFalse(UrlHost::isValueValid(true));
    }
    
    
    
}
