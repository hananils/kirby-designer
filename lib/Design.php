<?php

namespace Hananils;

use Kirby\Template\Snippet;

class Design extends Snippet
{
    public static $settings = [];

    public static function start(
        string|array|null $name,
        array $data = [],
        bool $slots = false,
        int $level = 1,
        bool $collapse = true,
        bool $trim = true,
        bool $return = false
    ) {
        if ($slots === true) {
            Design::$settings[] = [
                'level' => $level,
                'collapse' => $collapse,
                'trim' => $trim
            ];

            return Design::factory($name, $data, $slots);
        }

        $output = Design::factory($name, $data);
        $designer = Design::layout($output, $level, $collapse, $trim);

        if ($return === true) {
            return $designer->toHtml(false, false);
        }

        return $designer;
    }

    public static function end(): void
    {
        $output = static::$current?->render();

        echo static::layout($output, slots: true);
    }

    public static function layout(
        string $output = '',
        $level = 1,
        $collapse = false,
        $trim = false,
        $slots = false
    ): Designer {
        if ($slots === true) {
            extract(array_pop(static::$settings));
        }

        $designer = Designer::fromString($output);

        if ($level > 1) {
            $designer->level($level);
        }

        if ($collapse) {
            $designer->collapse(ignore: ['preformatted']);
        }

        if ($trim) {
            $designer->trim(ignore: ['phrasing', 'preformatted']);
        }

        return $designer;
    }
}
