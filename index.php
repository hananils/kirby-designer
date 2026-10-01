<?php

require_once __DIR__ . '/vendor/autoload.php';

use Hananils\Design;
use Hananils\Designer;
use Hananils\Plugins\PluginManager;
// use Hananils\Plus\PlusPlugin;

/**
 * Alternative helper to embed snippets from the snippet folder which offers
 * a direct way to read, traverse and manipulate the resulting
 * HTML.
 *
 * @param $settings The settings.
 */
function design(...$settings)
{
    return Design::start(...$settings);
}

/**
 * Closes a slotted `design` helper.
 */
function enddesign()
{
    Design::end();
}

/**
 * Helper to read, traverse and manipulate HTML documents or fragments.
 *
 * @param $html The HTML.
 */
function designer(string $html = '')
{
    return Designer::fromString($html);
}

PluginManager::autoload('hananils/designer', __DIR__);
