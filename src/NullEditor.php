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
 * No-op editor for contexts where no WYSIWYG editing is available.
 */
final class NullEditor implements EditorInterface
{
    public function initialize(
        string $textareaId = '',
        EditorVariant $variant = EditorVariant::Full,
        ?EditorConfig $config = null,
    ): EditorResult {
        return EditorResult::empty();
    }

    public function supportedByBrowser(): bool
    {
        return false;
    }
}
