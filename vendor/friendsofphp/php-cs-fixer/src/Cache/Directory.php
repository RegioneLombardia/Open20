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
final class Directory implements DirectoryInterface
{
    /**
     * @var string
     */
    private $directoryName;

    /**
     * @param string $directoryName
     */
    public function __construct($directoryName)
    {
        $this->directoryName = $directoryName;
    }

    /**
     * {@inheritdoc}
     */
    public function getRelativePathTo($file)
    {
        $file = $this->normalizePath($file);

        if (
            '' === $this->directoryName
            || 0 !== stripos($file, $this->directoryName.\DIRECTORY_SEPARATOR)
        ) {
            return $file;
        }

        return substr($file, \strlen($this->directoryName) + 1);
    }

    private function normalizePath($path)
    {
        return str_replace(['\\', '/'], \DIRECTORY_SEPARATOR, $path);
    }
}
