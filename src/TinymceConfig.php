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

use JsonException;

/**
 * TinyMCE-native configuration.
 *
 * Holds tinymce.init() options using TinyMCE-native keys.
 * Consumers building TinyMCE config pass TinyMCE options directly —
 * no abstraction pretense.
 */
final readonly class TinymceConfig
{
    /**
     * @param array<string, mixed> $options tinymce.init() key-value pairs
     */
    private function __construct(
        public array $options,
    ) {}

    /**
     * Create config from a TinyMCE options array.
     *
     * @param array<string, mixed> $options tinymce.init() options
     */
    public static function fromArray(array $options): self
    {
        return new self(options: $options);
    }

    /**
     * Create default config with sensible Horde defaults.
     */
    public static function default(): self
    {
        return new self(options: [
            'branding' => false,
            'promotion' => false,
            'menubar' => false,
        ]);
    }

    /**
     * Serialize options to a JSON string for use in tinymce.init().
     *
     * @throws EditorException If JSON encoding fails
     */
    public function toJs(): string
    {
        if ($this->options === []) {
            return '{}';
        }

        try {
            return json_encode($this->options, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new EditorException('Failed to encode TinyMCE config: ' . $e->getMessage(), 0, $e);
        }
    }
}
