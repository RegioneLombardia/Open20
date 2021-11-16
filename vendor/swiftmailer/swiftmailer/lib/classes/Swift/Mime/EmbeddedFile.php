<?php

/*
 * (c) 2004-2009 Chris Corbyn
 *
 */

/**
 * An embedded file, in a multipart message.
 *
 */
class Swift_Mime_EmbeddedFile extends Swift_Mime_Attachment
{
    /**
     * Creates a new Attachment with $headers and $encoder.
     *
     * @param array $mimeTypes optional
     */
    public function __construct(Swift_Mime_SimpleHeaderSet $headers, Swift_Mime_ContentEncoder $encoder, Swift_KeyCache $cache, Swift_IdGenerator $idGenerator, $mimeTypes = [])
    {
        parent::__construct($headers, $encoder, $cache, $idGenerator, $mimeTypes);
        $this->setDisposition('inline');
        $this->setId($this->getId());
    }

    /**
     * Get the nesting level of this EmbeddedFile.
     *
     * Returns {@see LEVEL_RELATED}.
     *
     * @return int
     */
    public function getNestingLevel()
    {
        return self::LEVEL_RELATED;
    }
}
