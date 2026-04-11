<?php

declare(strict_types=1);

/**
 * Copyright 2026 The Horde Project (http://www.horde.org/)
 *
 * See the enclosed file LICENSE for license information (LGPL). If you
 * did not receive this file, see http://www.horde.org/licenses/lgpl21.
 */

namespace Horde\Editor\Test\Unit;

use Horde_Editor_Exception;
use Horde_Exception_Wrapped;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Horde_Editor_Exception::class)]
class ExceptionTest extends TestCase
{
    public function testExceptionExtendsWrappedException(): void
    {
        $exception = new Horde_Editor_Exception('test error');

        $this->assertInstanceOf(Horde_Exception_Wrapped::class, $exception);
    }

    public function testExceptionCanBeThrown(): void
    {
        $this->expectException(Horde_Editor_Exception::class);
        $this->expectExceptionMessage('editor failure');

        throw new Horde_Editor_Exception('editor failure');
    }
}
