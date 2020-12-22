<?php declare(strict_types=1);

namespace Tests\Mediagone\Common\Types\Web;

use InvalidArgumentException;
use Mediagone\Common\Types\Web\UrlPath;
use PHPUnit\Framework\TestCase;
use function str_repeat;


/**
 * @covers \Mediagone\Common\Types\Web\UrlPath
 */
final class UrlPathTest extends TestCase
{
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_path_cannot_be_empty() : void
    {
        $this->expectException(InvalidArgumentException::class);
        UrlPath::fromString('');
    }
    
    
    public function test_can_reach_max_size() : void
    {
        $longPath = str_repeat('a', UrlPath::MAX_LENGTH - 1);
        
        self::assertInstanceOf(UrlPath::class, UrlPath::fromString('/' . $longPath));
    }
    
    
    public function test_path_cannot_be_too_long() : void
    {
        $longPath = str_repeat('a', UrlPath::MAX_LENGTH);
        
        $this->expectException(InvalidArgumentException::class);
        UrlPath::fromString('/' . $longPath . 'a');
    }
    
    
    
    //========================================================================================================
    // Scheme Tests
    //========================================================================================================
    
    public function test_path_must_start_with_a_slash() : void
    {
        $this->expectException(InvalidArgumentException::class);
        self::assertInstanceOf(UrlPath::class, UrlPath::fromString('hello'));
    }
    
    
    public function test_path_can_start_with_a_slash() : void
    {
        self::assertInstanceOf(UrlPath::class, UrlPath::fromString('/hello'));
    }
    
    
    /**
     * @dataProvider invalidPathProvider
     */
    public function test_path_cannot_contain_scheme_or_domain($url) : void
    {
        $this->expectException(InvalidArgumentException::class);
        UrlPath::fromString($url);
    }
    
    public function invalidPathProvider()
    {
        yield ['http://'];
        yield ['https://'];
        yield ['http://domain.com'];
        yield ['https://domain.com'];
        yield ['other://domain.com'];
        yield ['://domain.com'];
    }
    
    
    
    //========================================================================================================
    // Path Tests
    //========================================================================================================
    
    public function test_path_can_contain_letters() : void
    {
        self::assertInstanceOf(UrlPath::class, UrlPath::fromString('/abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'));
    }
    
    
    public function test_path_can_contain_digits() : void
    {
        self::assertInstanceOf(UrlPath::class, UrlPath::fromString('/abcdefghijklmnopqrstuvwxyz0123456789'));
    }
    
    
    public function test_path_can_contain_slashes() : void
    {
        self::assertInstanceOf(UrlPath::class, UrlPath::fromString('/abcdefghijklmnopqrstuvwxyz/ABCDEFGHIJKLMNOPQRSTUVWXYZ/0123456789/'));
    }
    
    
    public function test_path_can_contain_dots() : void
    {
        self::assertInstanceOf(UrlPath::class, UrlPath::fromString('/about.page.html'));
    }
    
    
    public function test_path_can_contain_misc_chars() : void
    {
        self::assertInstanceOf(UrlPath::class, UrlPath::fromString('/a-b#c[d]e\'f@g!h$i&j(k)l*m+n,o;p=q_r~s:t/u?v%wxyz'));
    }
    
    
    
    //========================================================================================================
    // Conversion tests
    //========================================================================================================
    
    public function test_can_be_converted_to_json() : void
    {
        $url = UrlPath::fromString('/some/path/to/page.html');
        self::assertSame('"\/some\/path\/to\/page.html"', json_encode($url));
    }
    
    
    public function test_can_be_converted_to_string() : void
    {
        $slug = UrlPath::fromString('/some/path/to/page.html');
        self::assertSame('/some/path/to/page.html', $slug->toString());
    }
    
    
    
    //========================================================================================================
    // Misc tests
    //========================================================================================================
    
    public function test_can_tell_non_string_value_is_invalid() : void
    {
        self::assertFalse(UrlPath::isValueValid(100));
        self::assertFalse(UrlPath::isValueValid(true));
    }
    
    
    
}
