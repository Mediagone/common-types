<?php declare(strict_types=1);

namespace Tests\Mediagone\Common\Types\Text;

use InvalidArgumentException;
use Mediagone\Common\Types\Text\Slug;
use PHPUnit\Framework\TestCase;
use function str_repeat;


/**
 * @covers \Mediagone\Common\Types\Text\Slug
 */
final class SlugTest extends TestCase
{
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_cannot_be_empty() : void
    {
        $this->expectException(InvalidArgumentException::class);
        Slug::fromString('');
    }
    
    
    public function test_can_contain_lowercase_letters() : void
    {
        self::assertInstanceOf(Slug::class, Slug::fromString('abcdefghijklmnopqrstuvwxyz'));
    }
    
    
    public function test_can_contain_digits() : void
    {
        self::assertInstanceOf(Slug::class, Slug::fromString('0123456789'));
    }
    
    
    /**
     * @dataProvider uppercaseProvider
     */
    public function test_cannot_contain_uppercase_letters(string $letter) : void
    {
        $this->expectException(InvalidArgumentException::class);
        Slug::fromString($letter);
    }
    
    public function uppercaseProvider() : array
    {
        return array_map(function($l) { return [$l]; }, range('A', 'Z'));
    }
    
    
    public function test_can_contain_hyphens() : void
    {
        self::assertInstanceOf(Slug::class, Slug::fromString('abc-def-ghi'));
    }
    
    
    public function test_cannot_contain_adjacent_hyphens() : void
    {
        $this->expectException(InvalidArgumentException::class);
        Slug::fromString('abc--def-ghi');
    }
    
    
    public function test_cannot_start_with_hyphen() : void
    {
        $this->expectException(InvalidArgumentException::class);
        Slug::fromString('-abc-def-ghi');
    }
    
    
    public function test_cannot_end_with_hyphen() : void
    {
        $this->expectException(InvalidArgumentException::class);
        Slug::fromString('abc-def-ghi-');
    }
    
    
    /**
     * @dataProvider accentProvider
     */
    public function test_cannot_contain_accents($letter) : void
    {
        $this->expectException(InvalidArgumentException::class);
        Slug::fromString($letter);
    }
    
    public function accentProvider()
    {
        yield ['é'];
        yield ['è'];
        yield ['ê'];
        yield ['à'];
        yield ['ù'];
    }
    
    
    public function test_cannot_be_too_long() : void
    {
        $longSlug = str_repeat('a', Slug::MAX_LENGTH);
        self::assertInstanceOf(Slug::class, Slug::fromString($longSlug));
        
        $this->expectException(InvalidArgumentException::class);
        Slug::fromString($longSlug.'a');
    }
    
    
    
    //========================================================================================================
    // Conversion tests
    //========================================================================================================
    
    public function test_can_be_encoded_to_json() : void
    {
        $value = 'valid-slug';
        $slug = Slug::fromString($value);
        
        self::assertSame('valid-slug', $slug->jsonSerialize());
    }
    
    
    public function test_can_be_cast_to_string() : void
    {
        $value = 'valid-slug';
        $slug = Slug::fromString($value);
        self::assertSame($value, $slug->toString());
    }
    
    
    
    //========================================================================================================
    // Misc
    //========================================================================================================
    
    public function test_can_tell_value_is_valid() : void
    {
        self::assertTrue(Slug::isValueValid('valid-slug'));
    }
    
    
    public function test_can_tell_non_string_value_is_invalid() : void
    {
        self::assertFalse(Slug::isValueValid(100));
        self::assertFalse(Slug::isValueValid(true));
        self::assertFalse(Slug::isValueValid('invalid slug'));
    }
    
    
}
