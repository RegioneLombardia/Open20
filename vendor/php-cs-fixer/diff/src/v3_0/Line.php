<?php
/*
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 */

namespace PhpCsFixer\Diff\v3_0;

final class Line
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

    public function __construct($type = self::UNCHANGED, $content = '')
    {
        $this->type    = $type;
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getType()
    {
        return $this->type;
    }
}
