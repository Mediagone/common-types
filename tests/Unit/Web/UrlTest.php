<?php declare(strict_types=1);

namespace Tests\Mediagone\Common\Types\Web;

use InvalidArgumentException;
use Mediagone\Common\Types\Web\Url;
use PHPUnit\Framework\TestCase;
use function str_repeat;
use function strlen;


/**
 * @covers \Mediagone\Common\Types\Web\Url
 */
final class UrlTest extends TestCase
{
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_cannot_be_empty() : void
    {
        $this->expectException(InvalidArgumentException::class);
        Url::fromString('');
    }
    
    
    public function test_can_reach_max_size() : void
    {
        $longPath = str_repeat('a', Url::MAX_LENGTH - strlen('https://domain.com/'));
        
        self::assertInstanceOf(Url::class, Url::fromString('https://domain.com/' . $longPath));
    }
    
    
    public function test_cannot_be_too_long() : void
    {
        $longPath = str_repeat('a', Url::MAX_LENGTH - strlen('https://domain.com/') + 1);
        
        $this->expectException(InvalidArgumentException::class);
        Url::fromString('https://domain.com/' . $longPath);
    }
    
    
    
    //========================================================================================================
    // Scheme Tests
    //========================================================================================================
    
    public function test_scheme_can_start_with_http() : void
    {
        self::assertInstanceOf(Url::class, Url::fromString('http://domain.com'));
    }
    
    
    public function test_scheme_can_start_with_https() : void
    {
        self::assertInstanceOf(Url::class, Url::fromString('https://domain.com'));
    }
    
    
    /**
     * @dataProvider invalidSchemeProvider
     */
    public function test_scheme_must_be_valid($url) : void
    {
        $this->expectException(InvalidArgumentException::class);
        Url::fromString($url);
    }
    
    public function invalidSchemeProvider()
    {
        yield ['domain.com'];
        yield ['://domain.com'];
        yield  ['other://domain.com'];
    }
    
    
    
    //========================================================================================================
    // Domain Tests
    //========================================================================================================
    
    public function test_domain_can_have_up_to_10_subdomains() : void
    {
        foreach (range(0, 10) as $count) {
            self::assertInstanceOf(Url::class, Url::fromString('https://' . str_repeat('www.', $count) . 'domain.com'));
        }
        
        $this->expectException(InvalidArgumentException::class);
        Url::fromString('https://' . str_repeat('www.', 11) . 'domain.com');
    }
    
    
    public function test_domain_cannot_be_too_long() : void
    {
        $longDomain = str_repeat('a', Url::DOMAIN_MAX_LENGTH - strlen('.com'));
        self::assertInstanceOf(Url::class, Url::fromString("https://$longDomain.com"));
        
        $this->expectException(InvalidArgumentException::class);
        self::assertInstanceOf(Url::class, Url::fromString("https://{$longDomain}a.com"));
    }
    
    
    public function test_domain_can_end_with_a_slash() : void
    {
        self::assertInstanceOf(Url::class, Url::fromString('https://domain.com/'));
    }
    
    
    
    public function test_domain_can_contain_lowercase_letters() : void
    {
        self::assertInstanceOf(Url::class, Url::fromString('https://abcdefghijklmnopqrstuvwxyz.abcdefghijklmnopqrstuvwxyz.com'));
    }
    
    
    public function test_domain_can_contain_uppercase_letters() : void
    {
        self::assertInstanceOf(Url::class, Url::fromString('https://ABCDEFGHIJKLMNOPQRSTUVWXYZ.ABCDEFGHIJKLMNOPQRSTUVWXYZ.com'));
    }
    
    
    public function test_domain_can_contain_digits() : void
    {
        self::assertInstanceOf(Url::class, Url::fromString('https://0123456789.0123456789.com'));
    }
    
    
    public function test_domain_can_contain_hyphens() : void
    {
        self::assertInstanceOf(Url::class, Url::fromString('https://abc-def-ghi.abc-def-ghi.com'));
    }
    
    
    public function test_domain_cannot_contain_adjacent_hyphens() : void
    {
        $this->expectException(InvalidArgumentException::class);
        Url::fromString('https://abc--def-ghi.com');
    }
    
    
    public function test_domain_cannot_start_with_hyphen() : void
    {
        $this->expectException(InvalidArgumentException::class);
        Url::fromString('https://-abc-def.com');
    }
    
    
    public function test_domain_cannot_end_with_hyphen() : void
    {
        $this->expectException(InvalidArgumentException::class);
        Url::fromString('https://abc-def-.com');
    }
    
    
    
    //========================================================================================================
    // Path Tests
    //========================================================================================================
    
    public function test_path_can_contain_letters() : void
    {
        self::assertInstanceOf(Url::class, Url::fromString('https://domain.com/abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'));
    }
    
    
    public function test_path_can_contain_digits() : void
    {
        self::assertInstanceOf(Url::class, Url::fromString('https://domain.com/abcdefghijklmnopqrstuvwxyz0123456789'));
    }
    
    
    public function test_path_can_contain_slashes() : void
    {
        self::assertInstanceOf(Url::class, Url::fromString('https://domain.com/abcdefghijklmnopqrstuvwxyz/ABCDEFGHIJKLMNOPQRSTUVWXYZ/0123456789/'));
    }
    
    
    public function test_path_can_contain_dots() : void
    {
        self::assertInstanceOf(Url::class, Url::fromString('https://domain.com/about.page.html'));
    }
    
    
    public function test_path_can_contain_misc_chars() : void
    {
        self::assertInstanceOf(Url::class, Url::fromString('https://domain.com/a-b#c[d]e\'f@g!h$i&j(k)l*m+n,o;p=q_r~s:t/u?v%wxyz'));
    }
    
    
    
    //========================================================================================================
    // Conversion tests
    //========================================================================================================
    
    public function test_can_be_converted_to_json() : void
    {
        $url = Url::fromString('http://domain.com');
        self::assertSame('"http:\/\/domain.com"', json_encode($url));
    }
    
    
    public function test_can_be_converted_to_string() : void
    {
        $slug = Url::fromString('http://domain.com');
        self::assertSame('http://domain.com', (string)$slug);
    }
    
    
    
    //========================================================================================================
    // Misc tests
    //========================================================================================================
    
    public function test_can_tell_non_string_value_is_invalid() : void
    {
        self::assertFalse(Url::isValueValid(100));
        self::assertFalse(Url::isValueValid(true));
    }
    
    
    
}
