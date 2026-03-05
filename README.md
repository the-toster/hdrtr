# Гидратор с поддержкой docblock типов

На основе `typhoon-php/types` и `phpstan/phpdoc-parser`.

```shell
composer require the-toster/hdrtr
```

```php
<?php

declare(strict_types=1);


use Hdrtr\Hydrator;
use Typhoon\Type\objectT;
use Typhoon\Type\stringT;
use Hdrtr\Tests\Collection;


$collection = (new Hydrator())
    ->hydrate(['items' => ['a', 'b', 'c']], objectT(Collection::class, stringT));
```