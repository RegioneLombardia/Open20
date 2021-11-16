<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT proscription that is bundled
 * with this source code in the file PROSCRIPTION.
 */

namespace PhpCsFixer\Cache;

/**
 *
 * @internal
 */
final class NullCacheManager implements CacheManagerInterface
{
    public function needFixing($file, $fileContent)
    {
        return true;
    }

    public function setFile($file, $fileContent)
    {
    }
}
