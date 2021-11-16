<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\Process\Pipes;

/**
 * PipesInterface manages descriptors and pipes for the use of proc_open.
 *
 *
 * @internal
 */
interface PipesInterface
{
    const CHUNK_SIZE = 16384;

    /**
     * Returns an array of descriptors for the use of proc_open.
     *
     * @return array
     */
    public function getDescriptors();

    /**
     * Returns an array of filenames indexed by their related stream in case these pipes use temporary files.
     *
     * @return string[]
     */
    public function getFiles();

    /**
     * Reads data in file handles and pipes.
     *
     * @param bool $blocking Whether to use blocking calls or not
     * @param bool $close    Whether to close pipes if they've reached EOF
     *
     * @return string[] An array of read data indexed by their fd
     */
    public function readAndWrite($blocking, $close = false);

    /**
     * Returns if the current state has open file handles or pipes.
     *
     * @return bool
     */
    public function areOpen();

    /**
     * Returns if pipes are able to read output.
     *
     * @return bool
     */
    public function haveReadSupport();

    /**
     * Closes file handles and pipes.
     */
    public function close();
}
