<?php

declare(strict_types=1);

/**
 * Copyright 2026 The Horde Project (http://www.horde.org/)
 *
 * See the enclosed file LICENSE for license information (LGPL). If you
 * did not receive this file, see http://www.horde.org/licenses/lgpl21.
 */

namespace Horde\Editor\Test\Unit;

use Horde\Editor\EditorInterface;
use Horde\Editor\EditorResult;
use Horde\Editor\NullEditor;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(NullEditor::class)]
class NullEditorTest extends TestCase
{
    public function testImplementsEditorInterface(): void
    {
        $this->assertInstanceOf(EditorInterface::class, new NullEditor());
    }

    public function testSupportedByBrowserReturnsFalse(): void
    {
        $this->assertFalse((new NullEditor())->supportedByBrowser());
    }

    public function testInitializeReturnsEmptyResult(): void
    {
        $result = (new NullEditor())->initialize(textareaId: 'test');

        $this->assertInstanceOf(EditorResult::class, $result);
        $this->assertSame([], $result->files);
        $this->assertSame([], $result->scripts);
    }
}
