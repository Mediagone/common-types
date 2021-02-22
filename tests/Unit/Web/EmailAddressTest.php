<?php declare(strict_types=1);

namespace Tests\Mediagone\Common\Types\Web;

use InvalidArgumentException;
use Mediagone\Common\Types\Web\EmailAddress;
use PHPUnit\Framework\TestCase;
use function range;
use function str_repeat;


/**
 * @covers \Mediagone\Common\Types\Web\EmailAddress
 */
final class EmailAddressTest extends TestCase
{
    //========================================================================================================
    // Atom tests
    //========================================================================================================
    
    public function test_atom_cannot_be_empty() : void
    {
        $this->expectException(InvalidArgumentException::class);
        EmailAddress::fromString('@domain.com');
    }
    
    
    public function test_atom_can_be_up_to_max_chars() : void
    {
        foreach (range(1, EmailAddress::MAX_ATOM_LENGTH) as $length) {
            $email = str_repeat('a', $length) . '@domain.com';
            self::assertInstanceOf(EmailAddress::class, EmailAddress::fromString($email));
        }
        
        $this->expectException(InvalidArgumentException::class);
        $emailWith31charsAtom = str_repeat('a', EmailAddress::MAX_ATOM_LENGTH + 1) . '@domain.com';
        EmailAddress::fromString($emailWith31charsAtom);
    }
    
    
    public function test_atom_can_contain_hyphen() : void
    {
        self::assertInstanceOf(EmailAddress::class, EmailAddress::fromString('atom-hyphens@domain.com'));
    }
    
    
    public function test_atom_can_contain_multiple_hyphens() : void
    {
        self::assertInstanceOf(EmailAddress::class, EmailAddress::fromString('atom-with-hyphens@domain.com'));
    }
    
    
    public function test_atom_cannot_contain_alongside_hyphens() : void
    {
        $this->expectException(InvalidArgumentException::class);
        EmailAddress::fromString('atom--hyphens@domain.com');
    }
    
    
    public function test_atom_cannot_contain_starting_hyphen() : void
    {
        $this->expectException(InvalidArgumentException::class);
        EmailAddress::fromString('-atom@domain.com');
    }
    
    
    public function test_atom_cannot_contain_trailing_hyphen() : void
    {
        $this->expectException(InvalidArgumentException::class);
        EmailAddress::fromString('atom-@domain.com');
    }
    
    
    public function test_atom_can_contain_dot() : void
    {
        self::assertInstanceOf(EmailAddress::class, EmailAddress::fromString('atom.dots@domain.com'));
    }
    
    
    public function test_atom_can_contain_multiple_dots() : void
    {
        self::assertInstanceOf(EmailAddress::class, EmailAddress::fromString('atom.with.dots@domain.com'));
    }
    
    
    public function test_atom_cannot_contain_alongside_dots() : void
    {
        $this->expectException(InvalidArgumentException::class);
        EmailAddress::fromString('atom..hyphens@domain.com');
    }
    
    
    public function test_atom_cannot_contain_starting_dot() : void
    {
        $this->expectException(InvalidArgumentException::class);
        EmailAddress::fromString('.atom@domain.com');
    }
    
    
    public function test_atom_cannot_contain_trailing_dot() : void
    {
        $this->expectException(InvalidArgumentException::class);
        EmailAddress::fromString('atom.@domain.com');
    }
    
    
    public function test_atom_cannot_contain_alongside_hyphen_and_dot() : void
    {
        $this->expectException(InvalidArgumentException::class);
        EmailAddress::fromString('atom.-atom@domain.com');
    }
    
    
    public function test_atom_cannot_contain_plus_chars() : void
    {
        $this->expectException(InvalidArgumentException::class);
        EmailAddress::fromString('atom+atom@domain.com');
        EmailAddress::fromString('+atom+atom+@domain.com');
    }
    
    
    public function test_atom_can_contain_uppercase() : void
    {
        self::assertInstanceOf(EmailAddress::class, EmailAddress::fromString('ATOM@domain.com'));
    }
    
    
    
    //========================================================================================================
    // Domain tests
    //========================================================================================================
    
    public function test_domain_cannot_be_empty() : void
    {
        $this->expectException(InvalidArgumentException::class);
        EmailAddress::fromString('atom@.com');
    }
    
    
    public function test_domain_can_be_up_to_max_chars() : void
    {
        foreach (range(1, EmailAddress::MAX_DOMAIN_LENGTH) as $length) {
            $email = 'atom@' . str_repeat('d', $length) . '.com';
            self::assertInstanceOf(EmailAddress::class, EmailAddress::fromString($email));
        }
        
        $this->expectException(InvalidArgumentException::class);
        $emailWith31charsDomain = 'atom@' . str_repeat('d', EmailAddress::MAX_DOMAIN_LENGTH + 1) . '.com';
        EmailAddress::fromString($emailWith31charsDomain);
    }
    
    
    public function test_domain_can_contain_hyphen() : void
    {
        self::assertInstanceOf(EmailAddress::class, EmailAddress::fromString('atom@domain-hyphens.com'));
    }
    
    
    public function test_domain_can_contain_multiple_hyphens() : void
    {
        self::assertInstanceOf(EmailAddress::class, EmailAddress::fromString('atom@domain-with-hyphens.com'));
    }
    
    
    public function test_domain_cannot_contain_alongside_hyphens() : void
    {
        $this->expectException(InvalidArgumentException::class);
        EmailAddress::fromString('atom@domain--hyphens.com');
    }
    
    
    public function test_domain_cannot_contain_starting_hyphen() : void
    {
        $this->expectException(InvalidArgumentException::class);
        EmailAddress::fromString('atom@-domain.com');
    }
    
    
    public function test_domain_cannot_contain_trailing_hyphen() : void
    {
        $this->expectException(InvalidArgumentException::class);
        EmailAddress::fromString('atom@domain-.com');
    }
    
    
    public function test_domain_cannot_contain_dots() : void
    {
        $this->expectException(InvalidArgumentException::class);
        EmailAddress::fromString('atom@dom.ain.com');
        EmailAddress::fromString('atom@do.ma.in.com');
    }
    
    
    public function test_domain_can_contain_uppercase() : void
    {
        self::assertInstanceOf(EmailAddress::class, EmailAddress::fromString('atom@DOMAIN.com'));
    }
    
    
    
    //========================================================================================================
    // Extension tests
    //========================================================================================================
    
    /**
     * @dataProvider invalidExtensionProvider
     */
    public function test_extension_cannot_be_too_short($extension) : void
    {
        $this->expectException(InvalidArgumentException::class);
        EmailAddress::fromString('atom@domain.'.$extension);
    }
    
    public function invalidExtensionProvider()
    {
        foreach (range(0, EmailAddress::MIN_EXTENSION_LENGTH - 1) as $length) {
            yield [str_repeat('e', $length)];
        }
    }
    
    
    public function test_extension_can_be_up_to_8_chars() : void
    {
        foreach (range(EmailAddress::MIN_EXTENSION_LENGTH, EmailAddress::MAX_EXTENSION_LENGTH) as $length) {
            $email = 'atom@domain.' . str_repeat('e', $length);
            self::assertInstanceOf(EmailAddress::class, EmailAddress::fromString($email));
        }
        
        $this->expectException(InvalidArgumentException::class);
        $emailWith31charsDomain = 'atom@domain.' . str_repeat('e', EmailAddress::MAX_EXTENSION_LENGTH + 1);
        EmailAddress::fromString($emailWith31charsDomain);
    }
    
    
    public function test_extension_can_contain_uppercase() : void
    {
        self::assertInstanceOf(EmailAddress::class, EmailAddress::fromString('atom@domain.COM'));
    }
    
    
    
    //========================================================================================================
    // Misc tests
    //========================================================================================================
    
    public function test_email_original_string_is_trimmed() : void
    {
        $email = EmailAddress::fromString(' local@domain.com ');
        self::assertSame('local@domain.com', (string)$email);
    }
    
    
    public function test_can_tell_non_string_value_is_invalid() : void
    {
        self::assertFalse(EmailAddress::isValueValid(100));
        self::assertFalse(EmailAddress::isValueValid(true));
    }
    
    
    
    //========================================================================================================
    // Conversion tests
    //========================================================================================================
    
    public function test_can_be_converted_to_json() : void
    {
        $email = EmailAddress::fromString('atom@domain.com');
        self::assertSame('"atom@domain.com"', json_encode($email));
    }
    
    
    public function test_can_be_cast_to_string() : void
    {
        $email = EmailAddress::fromString('atom@domain.com');
        self::assertSame('atom@domain.com', (string)$email);
    }
    
    
    
}
