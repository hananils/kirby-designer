<?php

use Hananils\Template;
use Kirby\Cms\App as Kirby;

return [
    /**
     * Custom template engine extending the default core engine. Only addition
     * is logic to make sure that open `design()` tags are getting closed
     * on render. Behaves like the default core engine otherwise.
     */
    'template' => function (
        Kirby $kirby,
        string $name,
        null|string $contentType = null
    ) {
        return new Template($name, $contentType);
    }
];
