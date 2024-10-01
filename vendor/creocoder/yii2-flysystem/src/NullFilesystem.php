<?php
/**
 */

namespace creocoder\flysystem;

use League\Flysystem\Adapter\NullAdapter;

/**
 * NullFilesystem
 *
 */
class NullFilesystem extends Filesystem
{
    /**
     * @return NullAdapter
     */
    protected function prepareAdapter()
    {
        return new NullAdapter();
    }
}
