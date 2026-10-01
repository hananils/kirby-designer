<?php

namespace Hananils;

use Kirby\Template\Template as KirbyTemplate;

/**
 * This custom template class is directly extending the core template class and
 * only adding a check for open `design()` tags on rendering. It otherwise works
 * and behaves exectly like the default template class.
 */
class Template extends KirbyTemplate
{
    /**
     * Renders the template with the given template data as the core template
     * would but makes sure that open `design()` tags are getting closed.
     */
    public function render(array $data = []): string
    {
        $output = parent::render($data);

        if ($this->type === 'html' && !empty(Design::$settings)) {
            // Close open design tags
            return Design::layout($output, slots: true);
        }

        return $output;
    }
}
