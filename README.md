# Common Types

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Software License][ico-license]](LICENSE)

Value Objects are small and **immutable** classes representing typed values usually implemented using PHP primitive types. However, objects can embed validation to ensure that your data is **always valid** without adding any check elsewhere in your code.

That's why you should ALWAYS use Value Objects rather than primitive types.


## Requirements
This package requires **PHP 7.4+**

Add it as Composer dependency:
```sh
$ composer require mediagone/common-types
```


## List of available Value Objects

All value objects implement a generic interface: `ValueObject`

### Text
- `Name`
- `Slug`
- `Text`
- `TextMedium`
- `Title`

### Web
- `EmailAddress`
- `Url`
- `UrlHost`
- `UrlPath`


## License

_Common Types_ is licensed under MIT license. See LICENSE file.



[ico-version]: https://img.shields.io/packagist/v/mediagone/common-types.svg
[ico-downloads]: https://img.shields.io/packagist/dt/mediagone/common-types.svg
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg

[link-packagist]: https://packagist.org/packages/mediagone/common-types
[link-downloads]: https://packagist.org/packages/mediagone/common-types
