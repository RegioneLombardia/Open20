<?php
/*
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 */

namespace PhpCsFixer\Diff\v1_4;

class Line
{
    const ADDED     = 1;
    const REMOVED   = 2;
    const UNCHANGED = 3;

    /**
     * @var int
     */
    private $type;

    /**
     * @var string
     */
    private $content;

    /**
     * @param int    $type
     * @param string $content
     */
    public function __construct($type = self::UNCHANGED, $content = '')
    {
        $this->type    = $type;
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }
}
