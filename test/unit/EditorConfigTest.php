<?php

declare(strict_types=1);

/**
 * Copyright 2026 The Horde Project (http://www.horde.org/)
 *
 * See the enclosed file LICENSE for license information (LGPL). If you
 * did not receive this file, see http://www.horde.org/licenses/lgpl21.
 */

namespace Horde\Editor\Test\Unit;

use Horde\Editor\EditorConfig;
use Horde\Editor\EditorException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(EditorConfig::class)]
class EditorConfigTest extends TestCase
{
    // ---------------------------------------------------------------
    // fromArray()
    // ---------------------------------------------------------------

    public function testFromArrayStoresOptions(): void
    {
        $config = EditorConfig::fromArray(['toolbar' => 'Basic']);

        $this->assertSame(['toolbar' => 'Basic'], $config->options);
    }

    public function testFromArrayDisablesSpellcheckByDefault(): void
    {
        $config = EditorConfig::fromArray([]);

        $this->assertTrue($config->disableSpellcheck);
    }

    public function testFromArrayCanEnableSpellcheck(): void
    {
        $config = EditorConfig::fromArray([], disableSpellcheck: false);

        $this->assertFalse($config->disableSpellcheck);
    }

    // ---------------------------------------------------------------
    // fromJsVariable()
    // ---------------------------------------------------------------

    public function testFromJsVariableStoresName(): void
    {
        $config = EditorConfig::fromJsVariable('myEditorConfig');

        $this->assertSame('myEditorConfig', $config->jsVariable);
        $this->assertSame([], $config->options);
    }

    // ---------------------------------------------------------------
    // default()
    // ---------------------------------------------------------------

    public function testDefaultHasEmptyOptionsAndDisabledSpellcheck(): void
    {
        $config = EditorConfig::default();

        $this->assertSame([], $config->options);
        $this->assertSame('', $config->jsVariable);
        $this->assertTrue($config->disableSpellcheck);
    }

    // ---------------------------------------------------------------
    // toJs()
    // ---------------------------------------------------------------

    public function testToJsWithJsVariableReturnsNameDirectly(): void
    {
        $config = EditorConfig::fromJsVariable('myConfig');

        $this->assertSame('myConfig', $config->toJs());
    }

    public function testToJsWithEmptyOptionsAndSpellcheckDisabledReturnsObject(): void
    {
        $config = EditorConfig::default();
        $result = $config->toJs();

        $decoded = json_decode($result, true);
        $this->assertIsArray($decoded);
        $this->assertFalse($decoded['scayt_autoStartup']);
    }

    public function testToJsWithEmptyOptionsAndSpellcheckEnabledReturnsEmptyObject(): void
    {
        $config = EditorConfig::fromArray([], disableSpellcheck: false);

        $this->assertSame('{}', $config->toJs());
    }

    public function testToJsWithOptionsSerializesToJson(): void
    {
        $config = EditorConfig::fromArray(['toolbar' => 'Basic', 'language' => 'en']);
        $result = $config->toJs();

        $decoded = json_decode($result, true);
        $this->assertSame('Basic', $decoded['toolbar']);
        $this->assertSame('en', $decoded['language']);
        $this->assertFalse($decoded['scayt_autoStartup']);
    }

    public function testToJsWithSpellcheckExplicitlyDisabledInOptions(): void
    {
        $config = EditorConfig::fromArray(
            ['scayt_autoStartup' => true],
            disableSpellcheck: true,
        );
        $result = $config->toJs();

        // disableSpellcheck flag overrides the option value
        $decoded = json_decode($result, true);
        $this->assertFalse($decoded['scayt_autoStartup']);
    }
}
