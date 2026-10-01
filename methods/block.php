<?php

use Hananils\Document\Elements;

return [
    'designer' => function ($format = null) {
        $field = $this->field();
        $html = $this->toHtml();

        if ($format === 'inline') {
            $html = strip_tags(
                (string) $html,
                Elements::byFormattingContext('inline')
            );
        }

        $designer = designer($html);
        $designer->field = $field;

        return $designer;
    }
];
