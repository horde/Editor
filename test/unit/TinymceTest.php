<?php

declare(strict_types=1);

/**
 * Copyright 2026 The Horde Project (http://www.horde.org/)
 *
 * See the enclosed file LICENSE for license information (LGPL). If you
 * did not receive this file, see http://www.horde.org/licenses/lgpl21.
 */

namespace Horde\Editor\Test\Unit;

use Horde\Editor\EditorResult;
use Horde\Editor\Tinymce;
use Horde\Editor\TinymceConfig;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Tinymce::class)]
class TinymceTest extends TestCase
{
    // ---------------------------------------------------------------
    // initialize() - file loading
    // ---------------------------------------------------------------

    public function testInitializeReturnsTinymceJsFile(): void
    {
        $result = (new Tinymce())->initialize();

        $this->assertContains('tinymce/tinymce.min.js', $result->files);
    }

    public function testInitializeReturnsSingleFile(): void
    {
        $result = (new Tinymce())->initialize();

        $this->assertCount(1, $result->files);
    }

    // ---------------------------------------------------------------
    // initialize() - textarea ID
    // ---------------------------------------------------------------

    public function testInitializeWithTextareaIdGeneratesInitScript(): void
    {
        $result = (new Tinymce())->initialize(textareaId: 'my_editor');

        $this->assertNotEmpty($result->scripts);
        $this->assertStringContainsString('tinymce.init(', $result->scripts[0]);
    }

    public function testInitializeWithTextareaIdIncludesSelector(): void
    {
        $result = (new Tinymce())->initialize(textareaId: 'my_editor');

        $this->assertStringContainsString('"selector":"#my_editor"', $result->scripts[0]);
    }

    public function testInitializeWithoutTextareaIdProducesNoScript(): void
    {
        $result = (new Tinymce())->initialize();

        $this->assertEmpty($result->scripts);
    }

    // ---------------------------------------------------------------
    // initialize() - config
    // ---------------------------------------------------------------

    public function testInitializeWithCustomConfig(): void
    {
        $config = TinymceConfig::fromArray(['toolbar' => 'bold italic', 'height' => 300]);
        $result = (new Tinymce())->initialize(textareaId: 'ed', config: $config);

        $script = $result->scripts[0];
        $this->assertStringContainsString('"toolbar":"bold italic"', $script);
        $this->assertStringContainsString('"height":300', $script);
    }

    public function testInitializeWithDefaultConfigIncludesDefaults(): void
    {
        $result = (new Tinymce())->initialize(textareaId: 'ed');

        $script = $result->scripts[0];
        $this->assertStringContainsString('"branding":false', $script);
        $this->assertStringContainsString('"promotion":false', $script);
        $this->assertStringContainsString('"menubar":false', $script);
    }

    public function testInitializeWithNullConfigUsesDefault(): void
    {
        $result = (new Tinymce())->initialize(textareaId: 'ed', config: null);

        $this->assertStringContainsString('tinymce.init(', $result->scripts[0]);
        $this->assertStringContainsString('"branding":false', $result->scripts[0]);
    }

    public function testInitializeConfigSelectorDoesNotOverrideExistingOptions(): void
    {
        $config = TinymceConfig::fromArray(['toolbar' => 'bold']);
        $result = (new Tinymce())->initialize(textareaId: 'ed', config: $config);

        $script = $result->scripts[0];
        // Both selector and original toolbar should be present
        $this->assertStringContainsString('"selector":"#ed"', $script);
        $this->assertStringContainsString('"toolbar":"bold"', $script);
    }

    // ---------------------------------------------------------------
    // initialize() returns EditorResult
    // ---------------------------------------------------------------

    public function testInitializeReturnsEditorResult(): void
    {
        $result = (new Tinymce())->initialize(textareaId: 'test');

        $this->assertInstanceOf(EditorResult::class, $result);
    }
}
