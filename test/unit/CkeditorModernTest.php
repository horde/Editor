<?php

declare(strict_types=1);

/**
 * Copyright 2026 The Horde Project (http://www.horde.org/)
 *
 * See the enclosed file LICENSE for license information (LGPL). If you
 * did not receive this file, see http://www.horde.org/licenses/lgpl21.
 */

namespace Horde\Editor\Test\Unit;

use Horde\Editor\Ckeditor;
use Horde\Editor\EditorConfig;
use Horde\Editor\EditorInterface;
use Horde\Editor\EditorResult;
use Horde\Editor\EditorVariant;
use Horde_Browser;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Ckeditor::class)]
class CkeditorModernTest extends TestCase
{
    private function createBrowserStub(string $browser, bool $hasRte): Horde_Browser
    {
        $stub = $this->createStub(Horde_Browser::class);
        $stub->method('getBrowser')->willReturn($browser);
        $stub->method('hasFeature')->willReturn($hasRte);

        return $stub;
    }

    // ---------------------------------------------------------------
    // Interface compliance
    // ---------------------------------------------------------------

    public function testImplementsEditorInterface(): void
    {
        $editor = new Ckeditor();

        $this->assertInstanceOf(EditorInterface::class, $editor);
    }

    // ---------------------------------------------------------------
    // supportedByBrowser()
    // ---------------------------------------------------------------

    public function testSupportedWithNoBrowserReturnsTrue(): void
    {
        $editor = new Ckeditor();

        $this->assertTrue($editor->supportedByBrowser());
    }

    /**
     * @return array<string, array{string}>
     */
    public static function supportedBrowserProvider(): array
    {
        return [
            'webkit'  => ['webkit'],
            'msie'    => ['msie'],
            'mozilla' => ['mozilla'],
            'opera'   => ['opera'],
        ];
    }

    #[DataProvider('supportedBrowserProvider')]
    public function testSupportedWithKnownBrowserAndRte(string $browser): void
    {
        $editor = new Ckeditor($this->createBrowserStub($browser, true));

        $this->assertTrue($editor->supportedByBrowser());
    }

    public function testNotSupportedWithUnknownBrowser(): void
    {
        $editor = new Ckeditor($this->createBrowserStub('lynx', true));

        $this->assertFalse($editor->supportedByBrowser());
    }

    public function testNotSupportedWithoutRteFeature(): void
    {
        $editor = new Ckeditor($this->createBrowserStub('webkit', false));

        $this->assertFalse($editor->supportedByBrowser());
    }

    // ---------------------------------------------------------------
    // initialize() - variant selection
    // ---------------------------------------------------------------

    public function testInitializeFullVariantLoadsFullEditor(): void
    {
        $result = (new Ckeditor())->initialize(variant: EditorVariant::Full);

        $this->assertContains('ckeditor/ckeditor.js', $result->files);
    }

    public function testInitializeBasicVariantLoadsBasicEditor(): void
    {
        $result = (new Ckeditor())->initialize(variant: EditorVariant::Basic);

        $this->assertContains('ckeditor/ckeditor_basic.js', $result->files);
    }

    public function testInitializeDefaultsToFullVariant(): void
    {
        $result = (new Ckeditor())->initialize();

        $this->assertContains('ckeditor/ckeditor.js', $result->files);
    }

    // ---------------------------------------------------------------
    // initialize() - textarea ID
    // ---------------------------------------------------------------

    public function testInitializeWithTextareaIdGeneratesScript(): void
    {
        $result = (new Ckeditor())->initialize(textareaId: 'my_editor');

        $this->assertNotEmpty($result->scripts);
        $this->assertStringContainsString('CKEDITOR.replace("my_editor"', $result->scripts[0]);
    }

    public function testInitializeWithoutTextareaIdProducesNoScript(): void
    {
        $result = (new Ckeditor())->initialize();

        $this->assertEmpty($result->scripts);
    }

    // ---------------------------------------------------------------
    // initialize() - config
    // ---------------------------------------------------------------

    public function testInitializeWithArrayConfig(): void
    {
        $config = EditorConfig::fromArray(['toolbar' => 'Basic']);
        $result = (new Ckeditor())->initialize(textareaId: 'ed', config: $config);

        $script = $result->scripts[0];
        $this->assertStringContainsString('toolbar', $script);
        $this->assertStringContainsString('scayt_autoStartup', $script);
    }

    public function testInitializeWithJsVariableConfig(): void
    {
        $config = EditorConfig::fromJsVariable('myConfig');
        $result = (new Ckeditor())->initialize(textareaId: 'ed', config: $config);

        $this->assertSame('CKEDITOR.replace("ed",myConfig)', $result->scripts[0]);
    }

    public function testInitializeWithDefaultConfigUsesEmptyObject(): void
    {
        $result = (new Ckeditor())->initialize(textareaId: 'ed');

        $this->assertStringContainsString('scayt_autoStartup', $result->scripts[0]);
    }

    public function testInitializeWithNullConfigUsesDefault(): void
    {
        $result = (new Ckeditor())->initialize(textareaId: 'ed', config: null);

        $this->assertStringContainsString('CKEDITOR.replace("ed"', $result->scripts[0]);
    }

    // ---------------------------------------------------------------
    // initialize() - unsupported browser
    // ---------------------------------------------------------------

    public function testInitializeReturnsEmptyResultWhenUnsupported(): void
    {
        $editor = new Ckeditor($this->createBrowserStub('lynx', false));
        $result = $editor->initialize(textareaId: 'ed');

        $this->assertSame([], $result->files);
        $this->assertSame([], $result->scripts);
    }

    // ---------------------------------------------------------------
    // initialize() returns EditorResult
    // ---------------------------------------------------------------

    public function testInitializeReturnsEditorResult(): void
    {
        $result = (new Ckeditor())->initialize(textareaId: 'test');

        $this->assertInstanceOf(EditorResult::class, $result);
    }
}
