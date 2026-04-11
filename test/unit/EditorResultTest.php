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
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(EditorResult::class)]
class EditorResultTest extends TestCase
{
    public function testConstructWithDefaults(): void
    {
        $result = new EditorResult();

        $this->assertSame([], $result->files);
        $this->assertSame([], $result->scripts);
    }

    public function testConstructWithValues(): void
    {
        $result = new EditorResult(
            files: ['ckeditor/ckeditor.js'],
            scripts: ['CKEDITOR.replace("test",{})'],
        );

        $this->assertSame(['ckeditor/ckeditor.js'], $result->files);
        $this->assertSame(['CKEDITOR.replace("test",{})'], $result->scripts);
    }

    public function testEmptyFactoryReturnsEmptyResult(): void
    {
        $result = EditorResult::empty();

        $this->assertSame([], $result->files);
        $this->assertSame([], $result->scripts);
    }
}
