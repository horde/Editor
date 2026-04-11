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

use Horde_Browser;

/**
 * CKEditor driver.
 *
 * Generates the JavaScript needed to embed a CKEditor WYSIWYG instance.
 */
final class Ckeditor implements EditorInterface
{
    public function __construct(
        private readonly ?Horde_Browser $browser = null,
    ) {}

    public function initialize(
        string $textareaId = '',
        EditorVariant $variant = EditorVariant::Full,
        ?EditorConfig $config = null,
    ): EditorResult {
        if (!$this->supportedByBrowser()) {
            return EditorResult::empty();
        }

        $file = match ($variant) {
            EditorVariant::Full  => 'ckeditor/ckeditor.js',
            EditorVariant::Basic => 'ckeditor/ckeditor_basic.js',
        };

        $config ??= EditorConfig::default();
        $scripts = [];

        if ($textareaId !== '') {
            $scripts[] = 'CKEDITOR.replace("' . $textareaId . '",' . $config->toJs() . ')';
        }

        return new EditorResult(
            files: [$file],
            scripts: $scripts,
        );
    }

    public function supportedByBrowser(): bool
    {
        if ($this->browser === null) {
            return true;
        }

        return match ($this->browser->getBrowser()) {
            'webkit', 'msie', 'mozilla', 'opera' => $this->browser->hasFeature('rte'),
            default => false,
        };
    }
}
