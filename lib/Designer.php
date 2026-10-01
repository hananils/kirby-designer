<?php

namespace Hananils;

use Hananils\Document\Inspector;
use Hananils\Fields\Reader;
use Kirby\Cms\App as Kirby;
use Kirby\Content\Field;
use Kirby\Toolkit\Str;

/**
 * Main Designer class.
 *
 * @copyright hana+nils · Büro für Gestaltung
 * @author Nils Hörrmann <nils.hoerrmann@hananils.de>
 * @link https://hananils.de
 * @license https://kirby.hananils.de/licenses/plus
 */
class Designer extends Inspector
{
    public ?Field $field = null;

    /**
     * Returns the source field – if Designer has a field reference –
     * applying all typographic changes first.
     *
     * Field and block methods will set the source field, the `designer()`
     * helper will have no reference by default.
     */
    public function toField(): ?Field
    {
        if ($this->field !== null) {
            $this->field->value = $this->toHtml();
        }

        return $this->field;
    }

    public function typographer(array $options = [], ?string $locale = null)
    {
        if (class_exists('\Hananils\Typographer\Typographer')) {
            if ($this->isFiltered()) {
                $this->reduce();
            } else {
                $this->clearSelection();
            }

            // Get locale
            if ($locale === null) {
                $locale = Reader::for($this->field)->locale();
            }

            $typographer = \Hananils\Typographer\Typographer::fromDocument(
                $this->document,
                $this->fragment,
                $locale,
                $options
            );
            $typographer->field = $this->field;

            return $typographer;
        }

        return $this;
    }

    /**
     * Reduces the document to the current selection.
     */
    public function reduce(): Inspector
    {
        $instance = static::fromNodes($this->toSelection());
        $instance->field = $this->field;

        return $instance;
    }

    /**
     * The snippet methods allows you to apply snippets to each direct child of
     * your current selection. Designer will match the element's name with the
     * snippet name: a paragraph `p` will match a `p.php` file, a `blockquote`
     * will match a `blockquote.php` file.
     *
     * @param string $path The path to the snippets relative to `site/snippets`.
     * @param array $data Additional data passed to the snippet.
     */
    public function snippets($path = 'elements', array $data = []): self
    {
        $kirby = Kirby::instance();
        $site = $kirby->site();
        $page = $site->page();

        $data['kirby'] = $kirby;
        $data['site'] = $site;
        $data['page'] = $page;
        $data['files'] = $page->files();
        $data['images'] = $page->images();

        $this->toSelection()->apply(
            $kirby->root('snippets') . '/' . $path,
            $data
        );

        return $this;
    }

    public function level(int $level = 1): Inspector
    {
        $this->toSelection()->level($level);

        return $this;
    }

    public function linkUsers(): Inspector
    {
        return $this;
    }

    public function linkPages(): Inspector
    {
        return $this;
    }

    public function linkFiles(): Inspector
    {
        return $this;
    }

    public function linkCollection(): Inspector
    {
        return $this;
    }

    public function wrapFrom(
        $selector,
        $element = 'div',
        $attributes = [],
        $xpath = false
    ): self {
        $boundaries = $this->select($selector, $xpath)->toSelection();

        foreach ($boundaries as $boundary) {
            $wrapper = $boundary->createElement($element, '', $attributes);
            $boundary->before($wrapper);

            $current = $boundary;
            while ($current) {
                $next = $current->next();
                $wrapper->append($current);

                $current = $next;
                if ($current && $current->matches($selector, $xpath)) {
                    break;
                }
            }
        }

        return $this;
    }

    public function generateId($node, $contextSelector = null, $xpath = false)
    {
        $context = $node;

        if ($contextSelector !== null) {
            $context = $xpath
                ? $node->query($contextSelector, $context->item())
                : $node->querySelectorAll($contextSelector, $context->item());
        }

        $text = $context->getText();
        $id = Str::slug($text);

        return $id;
    }

    public function generateAnchors($contextSelector = null, $xpath = false)
    {
        foreach ($this->toSelection() as $node) {
            if ($id = $this->generateId($node, $contextSelector, $xpath)) {
                $node->setId($id);
            }
        }

        return $this;
    }

    public function excerpt($boundary = null, $ellipsis = '&nbsp;…')
    {
        $text = '';

        // Handle empty documents
        if ($this->isEmpty()) {
            return $text;
        }

        foreach ($this->filterBy('name', '==', 'p')->toTexts() as $node) {
            foreach ($node->toWords() as $word) {
                $text .= ' ' . $word;

                if ($boundary && $boundary < mb_strlen($text)) {
                    break 2;
                }
            }
        }

        if ($text) {
            $text = trim($text);
        }

        // Shorten text if required
        if ($text && $boundary > 0) {
            // Remove orphaned punctation
            $text = trim($text, '-–,;:');

            // Add ellipsis, if we are not at the end of a sentence
            if (!str_ends_with($text, '.')) {
                $text .= $ellipsis;
            }

            // Remove orphaned words after shortening,
            // e. g. removes "It …" from "The summary. It …"
            $text = preg_replace(
                '/([.?!])\p{Zs}\p{L}+' . $ellipsis . '$/uim',
                '$1',
                $text
            );
        }

        return $text;
    }

    public function toAnchoredLinks(
        string $base = '',
        ?string $contextSelector = null,
        bool $xpath = false
    ) {
        foreach ($this->toSelection() as $node) {
            $id = $this->generateId($node, $contextSelector, $xpath);
            $link = $node->createElement('a', $node->text(), [
                'href' => $base . '#' . $id
            ]);
            $node->replace($link);
        }

        return $this;
    }

    /**
     * Returns the current Designer instance or `null` if it's empty. Helpful if
     * you want to populate a variable in a condition:
     *
     * ```php
     * if($nodes = $designer->filterBy('name', '==', 'p')->orNull()) {
     *     // This will only be executed, if the filter returns a result.
     * }
     * ```
     *
     * @return void  description
     */
    public function orNull()
    {
        if ($this->isEmpty()) {
            return null;
        }

        return $this;
    }
}
