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
 * Contract for WYSIWYG editor drivers.
 */
interface EditorInterface
{
    /**
     * Initialize the editor and return the JS assets needed.
     *
     * @param string          $textareaId HTML ID of the textarea to convert
     * @param EditorVariant   $variant    Full or basic (lightweight) editor
     * @param EditorConfig|null $config   Editor configuration; null uses default
     * @return EditorResult JS files and scripts needed to activate the editor
     */
    public function initialize(
        string $textareaId = '',
        EditorVariant $variant = EditorVariant::Full,
        ?EditorConfig $config = null,
    ): EditorResult;

    /**
     * Check whether the current browser supports this editor driver.
     */
    public function supportedByBrowser(): bool;
}
