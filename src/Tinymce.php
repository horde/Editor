<?php

declare(strict_types=1);

/**
 * Copyright 2026 The Horde Project (http://www.horde.org/)
 *
 * See the enclosed file LICENSE for license information (LGPL). If you
 * did not receive this file, see http://www.horde.org/licenses/lgpl21.
 *
 * @license  http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @package  Editor
 */

namespace Horde\Editor;

/**
 * TinyMCE driver.
 *
 * Generates the JavaScript needed to embed a TinyMCE WYSIWYG instance.
 * Standalone implementation — not part of the legacy EditorInterface
 * abstraction.
 */
final class Tinymce
{
    /**
     * Initialize TinyMCE and return the JS assets needed.
     *
     * @param string              $textareaId HTML ID of the textarea to convert
     * @param TinymceConfig|null  $config     TinyMCE configuration; null uses default
     * @return EditorResult JS files and scripts needed to activate the editor
     */
    public function initialize(
        string $textareaId = '',
        ?TinymceConfig $config = null,
    ): EditorResult {
        $config ??= TinymceConfig::default();
        $scripts = [];

        if ($textareaId !== '') {
            $options = $config->options;
            $options['selector'] = '#' . $textareaId;

            $initConfig = TinymceConfig::fromArray($options);
            $scripts[] = 'tinymce.init(' . $initConfig->toJs() . ')';
        }

        return new EditorResult(
            files: ['tinymce/tinymce.min.js'],
            scripts: $scripts,
        );
    }
}
