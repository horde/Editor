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
 * Typed editor configuration.
 *
 * Replaces the overloaded mixed config parameter from the legacy API.
 * Supports two modes: a key-value options array (serialized to JSON)
 * or a reference to a JavaScript variable name already defined on the page.
 */
final readonly class EditorConfig
{
    /**
     * @param array<string, mixed> $options      JS config key-value pairs
     * @param string               $jsVariable   JS variable name to use directly
     * @param bool                 $disableSpellcheck Whether to disable CKEditor SCAYT
     */
    private function __construct(
        public array $options,
        public string $jsVariable,
        public bool $disableSpellcheck,
    ) {}

    /**
     * Create config from a key-value options array.
     *
     * @param array<string, mixed> $options          JS config hash
     * @param bool                 $disableSpellcheck Disable spell-check-as-you-type
     */
    public static function fromArray(
        array $options,
        bool $disableSpellcheck = true,
    ): self {
        return new self(
            options: $options,
            jsVariable: '',
            disableSpellcheck: $disableSpellcheck,
        );
    }

    /**
     * Create config referencing a JS variable already on the page.
     *
     * @param string $variableName Name of the JS variable holding the config
     */
    public static function fromJsVariable(string $variableName): self
    {
        return new self(
            options: [],
            jsVariable: $variableName,
            disableSpellcheck: false,
        );
    }

    /**
     * Create a default empty config.
     */
    public static function default(): self
    {
        return new self(
            options: [],
            jsVariable: '',
            disableSpellcheck: true,
        );
    }

    /**
     * Serialize config for use in JavaScript.
     *
     * Returns a JS variable name if configured that way, or a JSON-encoded
     * string of the options array.
     *
     * @throws EditorException If JSON encoding fails
     */
    public function toJs(): string
    {
        if ($this->jsVariable !== '') {
            return $this->jsVariable;
        }

        $options = $this->options;

        if ($this->disableSpellcheck) {
            $options['scayt_autoStartup'] = false;
        }

        if ($options === []) {
            return '{}';
        }

        try {
            return json_encode($options, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new EditorException('Failed to encode editor config: ' . $e->getMessage(), 0, $e);
        }
    }
}
