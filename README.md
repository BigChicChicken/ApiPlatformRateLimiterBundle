# ApiPlatformRateLimiterBundle

[![Packagist](https://img.shields.io/packagist/v/bigchicchicken/api-platform-rate-limiter-bundle)](https://packagist.org/packages/bigchicchicken/api-platform-rate-limiter-bundle)

Bundle to controls how frequently certain APIs are allowed to be called for [ApiPlatform](https://api-platform.com/).

## Installation

Install ApiPlatformRateLimiterBundle library using [Composer](https://getcomposer.org/):

```bash
composer require bigchicchicken/api-platform-rate-limiter-group-bundle
```

Add/Check activation in the file `config/bundles.php`:

```php
// config/bundles.php

return [
    // ...
    ApiPlatformRateLimiterBundle\ApiPlatformRateLimiterBundle::class => ['all' => true],
];

```

Configure the [rate limiter](https://symfony.com/doc/current/rate_limiter.html) of Symfony :


```yaml
# config/packages/rate_limiter.yaml

framework:
    rate_limiter:
        fixed_window_5_requests_every_10_minutes:
            policy: 'fixed_window'
            limit: 5
            interval: '10 minutes'

```

And pass the rate limiter to your API's operation :

```php
<?php

// src/Entity/MyClass.php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;

#[ApiResource]
#[GetCollection(extraProperties: [ 'rate_limiter' => 'fixed_window_5_requests_every_10_minutes' ])]
class MyClass
{
    // ...
}
```

## License

This is completely free and released under the [MIT License](https://github.com/BigChicChicken/ApiPlatformRateLimiterBundle/blob/main/LICENSE).