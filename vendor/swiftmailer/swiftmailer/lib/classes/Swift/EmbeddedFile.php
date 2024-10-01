<?php

/*
 * (c) 2004-2009 Chris Corbyn
 *
 */

/**
 * An embedded file, in a multipart message.
 *
 */
class Swift_EmbeddedFile extends Swift_Mime_EmbeddedFile
{
    /**
     * Create a new EmbeddedFile.
     *
     * Details may be optionally provided to the constructor.
     *
     * @param string|Swift_OutputByteStream $data
     * @param string                        $filename
     * @param string                        $contentType
     */
    public function __construct($data = null, $filename = null, $contentType = null)
    {
        \call_user_func_array(
            [$this, 'Swift_Mime_EmbeddedFile::__construct'],
            Swift_DependencyContainer::getInstance()
                ->createDependenciesFor('mime.embeddedfile')
            );

        $this->setBody($data);
        $this->setFilename($filename);
        if ($contentType) {
            $this->setContentType($contentType);
        }
    }

    /**
     * Create a new EmbeddedFile from a filesystem path.
     *
     * @param string $path
     *
     * @return Swift_Mime_EmbeddedFile
     */
    public static function fromPath($path)
    {
        return (new self())->setFile(new Swift_ByteStream_FileByteStream($path));
    }
}
