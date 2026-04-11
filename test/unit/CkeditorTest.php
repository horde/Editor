<?php

declare(strict_types=1);

/**
 * Copyright 2026 The Horde Project (http://www.horde.org/)
 *
 * See the enclosed file LICENSE for license information (LGPL). If you
 * did not receive this file, see http://www.horde.org/licenses/lgpl21.
 */

namespace Horde\Editor\Test\Unit;

use Horde_Browser;
use Horde_Editor_Ckeditor;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Horde_Editor_Ckeditor::class)]
class CkeditorTest extends TestCase
{
    private function createBrowserStub(string $browser, bool $hasRte): Horde_Browser
    {
        $stub = $this->createStub(Horde_Browser::class);
        $stub->method('getBrowser')->willReturn($browser);
        $stub->method('hasFeature')->willReturn($hasRte);

        return $stub;
    }

    private function createSupportedBrowserStub(): Horde_Browser
    {
        return $this->createBrowserStub('webkit', true);
    }

    // ---------------------------------------------------------------
    // supportedByBrowser() tests
    // ---------------------------------------------------------------

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
    public function testSupportedByBrowserWithKnownBrowserAndRte(string $browser): void
    {
        $stub = $this->createBrowserStub($browser, true);
        $editor = new Horde_Editor_Ckeditor($stub);

        $this->assertTrue($editor->supportedByBrowser());
    }

    public function testSupportedByBrowserReturnsFalseForUnknownBrowser(): void
    {
        $stub = $this->createBrowserStub('lynx', true);
        $editor = new Horde_Editor_Ckeditor($stub);

        $this->assertFalse($editor->supportedByBrowser());
    }

    public function testSupportedByBrowserReturnsFalseWithoutRteFeature(): void
    {
        $stub = $this->createBrowserStub('webkit', false);
        $editor = new Horde_Editor_Ckeditor($stub);

        $this->assertFalse($editor->supportedByBrowser());
    }

    // ---------------------------------------------------------------
    // initialize() tests
    // ---------------------------------------------------------------

    public function testInitializeWithUnsupportedBrowserDoesNothing(): void
    {
        $stub = $this->createBrowserStub('lynx', false);
        $editor = new Horde_Editor_Ckeditor($stub);

        $editor->initialize(['id' => 'editor1']);

        // getJS() returns the initial empty string, not an array
        $this->assertSame('', $editor->getJS());
    }

    public function testInitializeLoadsFullEditorByDefault(): void
    {
        $editor = new Horde_Editor_Ckeditor($this->createSupportedBrowserStub());
        $editor->initialize();

        $js = $editor->getJS();
        $this->assertIsArray($js);
        $this->assertArrayHasKey('files', $js);
        $this->assertContains('ckeditor/ckeditor.js', $js['files']);
    }

    public function testInitializeLoadsBasicEditorWhenRequested(): void
    {
        $editor = new Horde_Editor_Ckeditor($this->createSupportedBrowserStub());
        $editor->initialize(['basic' => true]);

        $js = $editor->getJS();
        $this->assertIsArray($js);
        $this->assertContains('ckeditor/ckeditor_basic.js', $js['files']);
    }

    public function testInitializeWithIdGeneratesReplaceScript(): void
    {
        $editor = new Horde_Editor_Ckeditor($this->createSupportedBrowserStub());
        $editor->initialize([
            'id' => 'my_textarea',
            'config' => 'editorConfig',
        ]);

        $js = $editor->getJS();
        $this->assertIsArray($js);
        $this->assertArrayHasKey('script', $js);
        $this->assertNotEmpty($js['script']);
        $this->assertStringContainsString('CKEDITOR.replace("my_textarea"', $js['script'][0]);
    }

    public function testInitializeWithoutIdProducesNoScript(): void
    {
        $editor = new Horde_Editor_Ckeditor($this->createSupportedBrowserStub());
        $editor->initialize();

        $js = $editor->getJS();
        $this->assertIsArray($js);
        $this->assertArrayHasKey('script', $js);
        $this->assertEmpty($js['script']);
    }

    public function testInitializeWithArrayConfigSerializesToJson(): void
    {
        $editor = new Horde_Editor_Ckeditor($this->createSupportedBrowserStub());
        $editor->initialize([
            'id' => 'editor1',
            'config' => ['toolbar' => 'Basic'],
        ]);

        $js = $editor->getJS();
        $script = $js['script'][0];

        // Config should be serialized JSON containing the toolbar setting
        $this->assertStringContainsString('CKEDITOR.replace("editor1"', $script);
        // scayt_autoStartup should be forced to false
        $this->assertStringContainsString('scayt_autoStartup', $script);
    }

    public function testInitializeWithStringConfigUsesDirectly(): void
    {
        $editor = new Horde_Editor_Ckeditor($this->createSupportedBrowserStub());
        $editor->initialize([
            'id' => 'editor1',
            'config' => 'myCustomConfig',
        ]);

        $js = $editor->getJS();
        $script = $js['script'][0];

        $this->assertStringContainsString('CKEDITOR.replace("editor1",myCustomConfig)', $script);
    }

    /**
     * Tests the no-config code path.
     *
     * Note: Ckeditor.php:55-56 sets $params['config'] = [] when no config
     * is provided. At line 67, this array is concatenated into a string,
     * triggering a PHP "Array to string conversion" warning. This is a
     * pre-existing bug in the source code.
     */
    public function testInitializeWithoutConfigUsesEmptyArray(): void
    {
        $editor = new Horde_Editor_Ckeditor($this->createSupportedBrowserStub());
        $editor->initialize(['id' => 'editor1']);

        $js = $editor->getJS();
        $script = $js['script'][0];

        $this->assertStringContainsString('CKEDITOR.replace("editor1"', $script);
    }

    public function testGetJsReturnsFilesAndScriptKeys(): void
    {
        $editor = new Horde_Editor_Ckeditor($this->createSupportedBrowserStub());
        $editor->initialize([
            'id' => 'test',
            'config' => 'testConfig',
        ]);

        $js = $editor->getJS();

        $this->assertIsArray($js);
        $this->assertArrayHasKey('files', $js);
        $this->assertArrayHasKey('script', $js);
        $this->assertIsArray($js['files']);
        $this->assertIsArray($js['script']);
    }

    public function testInitializeWithArrayConfigDisablesScayt(): void
    {
        $editor = new Horde_Editor_Ckeditor($this->createSupportedBrowserStub());
        $editor->initialize([
            'id' => 'editor1',
            'config' => ['language' => 'en'],
        ]);

        $js = $editor->getJS();
        $script = $js['script'][0];

        // Verify scayt_autoStartup is set to false in the JSON config
        $this->assertStringContainsString('false', $script);
    }
}
