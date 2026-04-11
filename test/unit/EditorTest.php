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
use Horde_Editor;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Horde_Editor::class)]
class EditorTest extends TestCase
{
    public function testConstructorStoresBrowser(): void
    {
        $browser = $this->createStub(Horde_Browser::class);
        $editor = new Horde_Editor($browser);

        $this->assertInstanceOf(Horde_Editor::class, $editor);
    }

    public function testGetJsReturnsEmptyStringByDefault(): void
    {
        $browser = $this->createStub(Horde_Browser::class);
        $editor = new Horde_Editor($browser);

        $this->assertSame('', $editor->getJS());
    }

    public function testSupportedByBrowserReturnsFalse(): void
    {
        $browser = $this->createStub(Horde_Browser::class);
        $editor = new Horde_Editor($browser);

        $this->assertFalse($editor->supportedByBrowser());
    }

    public function testInitializeIsNoOp(): void
    {
        $browser = $this->createStub(Horde_Browser::class);
        $editor = new Horde_Editor($browser);

        $editor->initialize(['id' => 'test']);

        // After initialize, getJS still returns empty string (base class is a no-op)
        $this->assertSame('', $editor->getJS());
    }
}
