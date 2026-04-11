<?php

declare(strict_types=1);

/**
 * Copyright 2026 The Horde Project (http://www.horde.org/)
 *
 * See the enclosed file LICENSE for license information (LGPL). If you
 * did not receive this file, see http://www.horde.org/licenses/lgpl21.
 *
 * @license  http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @package  Editor
 */

namespace Horde\Editor;

/**
 * Editor loading variant.
 *
 * Controls whether the full editor or a lightweight stub is loaded.
 */
enum EditorVariant: string
{
    /** Load the complete editor upfront. */
    case Full = 'full';

    /** Load a small stub that downloads the full editor on demand. */
    case Basic = 'basic';
}
