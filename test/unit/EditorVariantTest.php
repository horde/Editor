<?php

declare(strict_types=1);

/**
 * Copyright 2026 The Horde Project (http://www.horde.org/)
 *
 * See the enclosed file LICENSE for license information (LGPL). If you
 * did not receive this file, see http://www.horde.org/licenses/lgpl21.
 */

namespace Horde\Editor\Test\Unit;

use Horde\Editor\EditorVariant;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(EditorVariant::class)]
class EditorVariantTest extends TestCase
{
    public function testFullVariantHasCorrectValue(): void
    {
        $this->assertSame('full', EditorVariant::Full->value);
    }

    public function testBasicVariantHasCorrectValue(): void
    {
        $this->assertSame('basic', EditorVariant::Basic->value);
    }

    public function testFromStringFull(): void
    {
        $this->assertSame(EditorVariant::Full, EditorVariant::from('full'));
    }

    public function testFromStringBasic(): void
    {
        $this->assertSame(EditorVariant::Basic, EditorVariant::from('basic'));
    }

    public function testTryFromInvalidReturnsNull(): void
    {
        $this->assertNull(EditorVariant::tryFrom('unknown'));
    }

    public function testEnumHasTwoCases(): void
    {
        $this->assertCount(2, EditorVariant::cases());
    }
}
