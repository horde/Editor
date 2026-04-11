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
 * Result of initializing an editor.
 *
 * Contains the JS files to load and the JS statements to execute.
 * Returned by EditorInterface::initialize() instead of mutating internal state.
 */
final readonly class EditorResult
{
    /**
     * @param list<string> $files   JS files that need to be loaded by the browser
     * @param list<string> $scripts JS statements to execute after loading
     */
    public function __construct(
        public array $files = [],
        public array $scripts = [],
    ) {}

    /**
     * Create an empty result (no files, no scripts).
     */
    public static function empty(): self
    {
        return new self();
    }
}
