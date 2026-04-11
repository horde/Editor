<?php

declare(strict_types=1);

/**
 * Copyright 2026 The Horde Project (http://www.horde.org/)
 *
 * See the enclosed file LICENSE for license information (LGPL). If you
 * did not receive this file, see http://www.horde.org/licenses/lgpl21.
 */

namespace Horde\Editor\Test\Unit;

use Horde\Editor\EditorException;
use Horde\Editor\TinymceConfig;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(TinymceConfig::class)]
class TinymceConfigTest extends TestCase
{
    // ---------------------------------------------------------------
    // default()
    // ---------------------------------------------------------------

    public function testDefaultDisablesBranding(): void
    {
        $config = TinymceConfig::default();

        $this->assertFalse($config->options['branding']);
    }

    public function testDefaultDisablesPromotion(): void
    {
        $config = TinymceConfig::default();

        $this->assertFalse($config->options['promotion']);
    }

    public function testDefaultDisablesMenubar(): void
    {
        $config = TinymceConfig::default();

        $this->assertFalse($config->options['menubar']);
    }

    // ---------------------------------------------------------------
    // fromArray()
    // ---------------------------------------------------------------

    public function testFromArrayStoresOptions(): void
    {
        $options = ['toolbar' => 'bold italic', 'height' => 300];
        $config = TinymceConfig::fromArray($options);

        $this->assertSame($options, $config->options);
    }

    public function testFromArrayWithEmptyArray(): void
    {
        $config = TinymceConfig::fromArray([]);

        $this->assertSame([], $config->options);
    }

    // ---------------------------------------------------------------
    // toJs()
    // ---------------------------------------------------------------

    public function testToJsSerializesToJson(): void
    {
        $config = TinymceConfig::fromArray(['toolbar' => 'bold italic']);
        $result = $config->toJs();

        $decoded = json_decode($result, true);
        $this->assertSame('bold italic', $decoded['toolbar']);
    }

    public function testToJsWithDefaultConfigIncludesAllDefaults(): void
    {
        $config = TinymceConfig::default();
        $result = $config->toJs();

        $decoded = json_decode($result, true);
        $this->assertFalse($decoded['branding']);
        $this->assertFalse($decoded['promotion']);
        $this->assertFalse($decoded['menubar']);
    }

    public function testToJsWithEmptyOptionsReturnsEmptyObject(): void
    {
        $config = TinymceConfig::fromArray([]);

        $this->assertSame('{}', $config->toJs());
    }

    public function testToJsThrowsEditorExceptionOnEncodingFailure(): void
    {
        $this->expectException(EditorException::class);
        $this->expectExceptionMessageMatches('/Failed to encode TinyMCE config/');

        // NAN cannot be JSON-encoded
        $config = TinymceConfig::fromArray(['value' => NAN]);
        $config->toJs();
    }
}
