<?php

/*
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 */

namespace Imagine\Image\Metadata;

/**
 * A metadata reader that actually doesn't try to extract metadata.
 */
class DefaultMetadataReader extends AbstractMetadataReader
{
    /**
     * {@inheritdoc}
     *
     */
    protected function extractFromFile($file)
    {
        return array();
    }

    /**
     * {@inheritdoc}
     *
     */
    protected function extractFromData($data)
    {
        return array();
    }

    /**
     * {@inheritdoc}
     *
     */
    protected function extractFromStream($resource)
    {
        return array();
    }
}
